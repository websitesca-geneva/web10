/**
 * @license Copyright (c) 2010-2011 Brian Cavalier
 * LICENSE: see the LICENSE.txt file. If file is missing, this file is subject
 * to the MIT License at: http://www.opensource.org/licenses/mit-license.php.
 */

/**
 * store.js
 * wire plugin that provides a REST resource reference resolver.  Referencing
 * any REST resource using the format: resource!url/goes/here will create a
 * dojo.store.JsonRest pointing to url/goes/here.  Using the id or query
 * options, you can alternatively resolve references to actual data.
 */
define([], function() {

    /**
     * If wait === true, waits for dataPromise to complete and resolves
     * the reference to the resulting concrete data.  If wait !== true,
     * resolves the reference to dataPromise.
     * 
     * @param dataPromise
     * @param resolver
     * @param wait
     */
	function resolveData(dataPromise, resolver, wait) {
		if(wait === true) {
			dataPromise.then(
				function(data) {
					resolver.resolve(data);
				},
				function(err) {
					resolver.reject(err);
				}
			);
		} else {
			resolver.resolve(dataPromise);
		}
	}

    /**
     * Resolves a dojo.store.JsonRest for the REST resource at the url
     * specified in the reference, e.g. resource!url/to/resource
     *      
     * @param resolver
     * @param name
     * @param refObj
     * @param wire
     */
	function resolveResource(resolver, name, refObj, wire) {
		wire({ create: { module: 'dojo/store/JsonRest', args: { target: name } } })
			.then(function(store) {
				if(refObj.get) {
					// If get was specified, get it, and resolve with the resulting item.
					resolveData(store.get(refObj.get), resolver, refObj.wait);

				} else if(refObj.query) {
					// Similarly, query and resolve with the result set.
					resolveData(store.query(refObj.query), resolver, refObj.wait);

				} else {
					// Neither get nor query was specified, so resolve with
					// the store itself.
					resolver.resolve(store);
				}						
			});
	}

    /**
     * The plugin instance.  Can be the same for all wiring runs
     */
    var plugin = {
        resolvers: {
            resource: resolveResource
        }
    };

	return {
		wire$plugin: function restPlugin(/* ready, destroyed, options */) {
            return plugin;
		}
	};
});
