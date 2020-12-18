var serviceWorkerStatus = 'ON';
var serviceWorkerURL = 'worker.js';

if (serviceWorkerStatus == 'ON' && 'serviceWorker' in navigator) {
    navigator.serviceWorker
    .register(serviceWorkerURL)
    .then(function() {
        console.log('[SW] Registered');
    }).catch(function(error){
        console.log(error);
    });
}

var preCacheName = 'pwa-cit-cache-init';
var dataCacheName = 'pwa-cit-cache-data';
var filesToCache = [
//    './index.html'
];
var pathsToAvoid = [
    "admin/",
    "WS/",
    "V1/",
    "NS/"
]; 

self.addEventListener('install', function (e) {
    console.log('[SW] Install');
    e.waitUntil(
        caches.open(preCacheName).then(function (cache) {
            console.log('[SW] Caching App Shell');
            return cache.addAll(filesToCache);
        })
    );
});

self.addEventListener('activate', function (e) {
    e.waitUntil(
        caches.keys().then(function (keyList) {
            return Promise.all(keyList.map(function (key) {
                if (key != preCacheName && key != dataCacheName) {
                    console.log('[SW] Removing Old Cache', key);
                    return caches.delete(key);
                }
            }));
        })
    );
    return self.clients.claim();
});

self.addEventListener('fetch', function(event) {
    for(var i = 0; i < pathsToAvoid.length; i++){
        if(event.request.url.match(self.registration.scope + pathsToAvoid[i])){
            return;
        }
    }
    if(event.request.method == "POST") {
        if(!navigator.onLine){
            sendMessage("You seems to be offline, please check your internet connectivity", "Offline", 0);
        }
    } else {
        event.respondWith(
          caches.match(event.request)
            .then(function(response) {
              // Cache hit - return response
              if (response) {
                return response;
              }

              // IMPORTANT: Clone the request. A request is a stream and
              // can only be consumed once. Since we are consuming this
              // once by cache and once by the browser for fetch, we need
              // to clone the response.
              var fetchRequest = event.request.clone();

              return fetch(fetchRequest).then(
                function(response) {
                  // Check if we received a valid response
                  if(!response || response.status !== 200 || response.type !== 'basic') {
                    return response;
                  }

                  // IMPORTANT: Clone the response. A response is a stream
                  // and because we want the browser to consume the response
                  // as well as the cache consuming the response, we need
                  // to clone it so we have two streams.
                  var responseToCache = response.clone();

                  caches.open(dataCacheName)
                    .then(function(cache) {
                      cache.put(event.request, responseToCache);
                    });

                  return response;
                }
              ).catch(function(e) {
                  sendMessage("You seems to be offline, the current requested content is not available for offline", "Offline", 0);
              });
            }).catch(function(e) {
                console.log("[SW] Cache Match Failed");
            })
        );

        event.waitUntil(
            caches.open(dataCacheName).then(function (cache) {
                var fetchRequest = event.request.clone();

                return fetch(fetchRequest).then(
                  function(response) {
                    // Check if we received a valid response
                    if(!response || response.status !== 200 || response.type !== 'basic') {
                      return response;
                    }
                    cache.put(event.request, response);
                  }
                ).catch(function(e) {
                    console.log("Offline, the current requested content is not available for offline", "Offline", 0);
                });
            })
        );
    }
});

function sendMessage(message, title, status){
    self.clients.matchAll().then(function(client) {
        //console.log("Offline");
        client[0].postMessage({
            message: message,
            title: title,
            status: status,
            options:{}
        });
    });
}
    