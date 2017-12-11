<?php

namespace KBox\Traits;

use BadMethodCallException;
use Exception;
use Klink\DmsSearch\SearchRequest;
use KBox\Pagination\SearchResultsPaginator;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;

/**
 * Add support for faster access to search features
 */
trait Searchable
{

    
    /**
     * Execute a search based on the given SearchRequest.
     * In case the SearchRequest->isPageRequested() is equal to true the $override closure will be called (if not null)
     *
     * @param SearchRequest $request the search request
     * @param \Closure $override the callback that will be called if SearchRequest->isPageRequested() in order to search for local available Models, to the callback the SearchRequest object in $request is passed as first argument
     * @param callable $each_result_callback the callback that will be called for each search result after getting the search results from the K-Link Core and before returning them. Usefull for converting search results to local Model instances. To the callback the Collection of results will be passed.
     * @return SearchResultsPaginator the paginated results
     */
    public function search(SearchRequest $request, \Closure $override = null, callable $each_result_callback = null)
    {
        
          // You can return false in $override to tell the system to use a normal search invocation
          
          $override_response = (! is_null($override) && $override) ? $override($request) : false;
          
        if (! is_bool($override_response) && ! is_a($override_response, 'Illuminate\Support\Collection')
                && ! is_a($override_response, 'Illuminate\Database\Eloquent\Builder')
                && ! is_a($override_response, 'Illuminate\Database\Eloquent\Relations\Relation')) {
            throw new BadMethodCallException(sprintf('Bad $override callback return value. Expected boolean===false || Eloquent\Builder || Collection || Illuminate\Database\Eloquent\Relations\Relation, received "%s (parent: %s)"', get_class($override_response), get_parent_class($override_response)));
        }
          
        if ($override_response !== false && ! $request->isSearchRequested()) {
            $total = (method_exists($override_response, 'getCountForPagination')) ? $override_response->getCountForPagination() : $override_response->count();

            $to_highlight = $request->highlight;

              // someone wants to highlight a document?
  
              if (! is_null($to_highlight) && $to_highlight != 0) {
                  // if collection, we already have all the results,
                  // so we need to retrieve the element with ID == $to_highlight
                  // and calculate its offset
  
                  $new_page = $request->page;
  
                  if (is_a($override_response, 'Illuminate\Support\Collection')) {
                      $key = $override_response->where('id', $to_highlight)->
                             keys()->first();
  
                      $new_page = floor($key / $request->limit) + 1;
                  } else {
                      
                      // duplicate the query to not change the original meaning
                      $row_count_query = clone $override_response;

                      // counting how many elements we have before the chosen one
                      // For this we use MariaDB session variables, because the counter
                      // is not available by default

                      \DB::statement(\DB::raw('set @row=0'));

                      $key = $row_count_query->select(\DB::raw('@row:=@row+1 as row'), 'id')->get(['row', 'id'])
                          ->where('id', $to_highlight)->first()->row - 1; // row is base 1
                      
                      $new_page = floor($key / $request->limit) + 1;
                  }
  
                  // then edit requested page accordingly
                  $request->page(intval($new_page, 10));
              }

            $paginated = $total === 0 ? new Collection() : (is_a($override_response, 'Illuminate\Support\Collection') ? $override_response->forPage($request->page, $request->limit)->values() : $override_response->forPage($request->page, $request->limit)->get());

            return new SearchResultsPaginator(
                    $request->term === '*' ? '' : $request->term,
                    $paginated,
                    null,
                    $this->facets($request),
                    $total,
                    $request->limit, $request->page, [
                        'path'  => $request->url,
                        'query' => collect($request->query)->except('highlight')->toArray(),
                    ]);
        } else {
            $core_results = app('Klink\DmsSearch\SearchService')->search($request);
              
            if (! is_null($each_result_callback) && ! is_null($core_results)) {
                // if the callback is defined, let's use it for mapping current search results to the instances that the developers prefer
                  $core_results->map($each_result_callback);
            }
              
            return $core_results;
        }
    }
    

    /**
     * Retrieve the facets for subsequent filtering
     *
     *
     */
    public function facets(SearchRequest $request)
    {
        $service = app('Klink\DmsSearch\SearchService');

        try {
            if (! $request->is_facets_forced && ! $request->isSearchRequested() && $request->isPageRequested()) {
                return $service->defaultFacets($request->visibility);
            }
            
            $ft_response = app('Klink\DmsSearch\SearchService')->search($request);
            
            if (is_null($ft_response)) {
                \Log::error('Unexpected NULL Searchable@facets() response.', ['request' => $request]);
                return [];
            }
            
            return $ft_response->facets();
        } catch (Exception $ex) {
            \Log::error('Unexpected error Searchable@facets().', ['request' => $request, 'error' => $ex]);
            return [];
        }
    }
    
    public function searchRequestCreate(Request $request = null)
    {
        return SearchRequest::create($request);
    }
}
