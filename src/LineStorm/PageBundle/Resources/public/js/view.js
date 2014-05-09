require(['jquery', 'highlightjs'], function($, hjs){
    hljs.initHighlightingOnLoad();
    $(document).ready(function(){
        $('pre code').each(function(i, e) {hljs.highlightBlock(e);});
    });
});
