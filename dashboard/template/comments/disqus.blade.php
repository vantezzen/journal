<div id="disqus_thread" class="comments disqus"></div>
<script>

var disqus_config = function () {
this.page.url = '{{ $full_url }}';
this.page.identifier = '{{ $identifier }}';
};
(function() { // DON'T EDIT BELOW THIS LINE
var d = document, s = d.createElement('script');
s.src = 'https://{{ $prefix }}.disqus.com/embed.js';
s.setAttribute('data-timestamp', +new Date());
(d.head || d.body).appendChild(s);
})();
</script>
<noscript>Please enable JavaScript to view the <a href="https://disqus.com/?ref_noscript">comments powered by Disqus.</a></noscript>