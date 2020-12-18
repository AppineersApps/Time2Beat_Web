<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <%$manifest_file%> ><body><script type="text/javascript">
    var logout_ready = parseInt('<%$logout_ready%>'),update_ready = '<%$update_ready%>',cache_status = '<%$app_cache_status%>';
    if (update_ready == '1') {
        var appCache = window.applicationCache;
        appCache.addEventListener('cached', parent.cacheEvent.handleEvent, false);
        appCache.addEventListener('checking', parent.cacheEvent.handleEvent, false);
        appCache.addEventListener('downloading', parent.cacheEvent.handleEvent, false);
        appCache.addEventListener('noupdate', parent.cacheEvent.handleEvent, false);
        appCache.addEventListener('progress', parent.cacheEvent.handleEvent, false);
        appCache.addEventListener('updateready', parent.cacheEvent.handleEvent, false);
        appCache.addEventListener('obsolete', parent.cacheEvent.handleEvent, false);
        appCache.addEventListener('error', parent.cacheEvent.handleEvent, false);
    }
</script></body></html>