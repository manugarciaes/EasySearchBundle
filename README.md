# SearchBundle for ElasticaBundle

1) You need Fos Elastica Bundle installed and configurated
2) Add SearchBundle to your appKernel
    
    $bundles = array(
    [..]
        new manugarciaes\SearchBundle\SearchBundle(),
    );
    
3) Change your Fos Elastica Bundle configuration, using as repository the next one

     persistence:
        [..]
        repository: manugarciaes\SearchBundle\Repository\SearchRepository


4) Create a new service using the Easy Search Service like next example:
    - elastica_entity is the fos elastica entity in your configuration
    - search_string is the fields of your entity where you want to search
 
      ##  EXAMPLE  
      article.search:
        class: SearchBundle\Services\SearchService
        arguments:
          - "@fos_elastica.manager.orm"
          - %elastica_entity%
          - ['%search_string%']
 
**If you want Add Filters**

1) create a new service with Easy Search Filter Object
    
        ## FILTER EXAMPLE
        elastic.search.filter.enabled:
          class: SearchBundle\Filter\ObjectFilter
          calls:
                - [setField, ['enabled']]
                - [setValue, [true]]
                
If you want a dynamic value you can use for example a service or something like
this:  

       - [setValue, ["@=service('request_stack').getMasterRequest.get('enabled')"]]

With this your filter change the value using the request
            
2) Add this filter to your Search Service
                                   
      ##  EXAMPLE
      article.search:
        class: SearchBundle\Services\SearchService
        arguments:
          - "@fos_elastica.manager.orm"
          - %elastica_entity%
          - ['%search_string%']
        calls:
          - [addFilter, ['@elastic.search.filter.enabled']]
            

**If you want add Aggregations**

1) Create a new service with Easy Search Aggregation Object

            ## example
            elastic.search.aggregation.categories:
              class: Elastica\Aggregation\Terms
              arguments:
                - %name%
              calls:
                - [setField, ['%category_field%']]
                - [setSize, [%category_size%]]

2) Add this aggregation to your Easy Search Service
 
  ##  EXAMPLE
       article.search:
         class: SearchBundle\Services\SearchService
         arguments:
           - "@fos_elastica.manager.orm"
           - %elastica_entity%
           - ['%search_string%']
         calls:
           - [addAggregation, [@elastic.search.aggregation.categories]]
                      
           
Now you can make a search using this service, example:
           
           $searchService = $this->get('article.search');
           $results = $searchService->search('hello');
           
           