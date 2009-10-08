<?PHP

include "../index.php";

$shell['title3'] = "urlInternal / urlExternal / urlFragment";

$shell['h2'] = 'Easily test URL internal-, external- or fragment-ness.';

// ========================================================================== //
// SCRIPT
// ========================================================================== //

ob_start();
?>
$(function(){
  var loc = document.location,
    fragment1 = loc.href.replace( /#.*$/, '' ) + '#test-anchor',
    fragment2 = loc.href.replace( /^https?:\/\/(?:.*?)(\/[^#]*)#?.*$/i, '$1' ) + '#test-anchor';
  
  $('#fragment1').attr({ href: fragment1 }).html( '<span>' + fragment1 + '</span>' );
  $('#fragment2').attr({ href: fragment2 }).html( '<span>' + fragment2 + '</span>' );
  
  $.elemUrlAttr({ span: 'data-url' });
  
  $('table tr.header th').attr( 'colSpan', 1 );
  
  function do_tests( title, callback ) {
    $('table')
      .find('tr.header th')
        .each(function(){
          var that = $(this);
          that.attr( 'colSpan', Number( that.attr( 'colSpan' ) ) + 1 );
        })
      .end()
      .find('tr.meta')
        .append('<td>' + title + '<\/td>')
      .end()
      .find('tr:not([class])')
        .each(function(){
          var that = $(this),
            elems = that.find('td:first-child').children();
          
          that.append('<td>' + callback.call( elems ) + '<\/td>');
        });
  };
  
  function callback_urlInternal() {
    var length = this.length;
    
    return this.urlInternal().length === length ? '<span class="int" title="This URL is internal">internal<\/span>'
      : this.urlExternal().length === length ? '<span class="ext" title="This URL is external">external<\/span>'
      : '<span class="non" title="This URL is neither internal nor external">non-nav<\/span>';
  };
  
  function callback_urlFragment() {
    var length = this.length;
    
    return this.urlFragment().length === length ? '<span class="frag" title="This URL is a fragment">fragment<\/span>'
      : '<span class="non" title="This URL is not a fragment">non-frag<\/span>';
  };
  
  $.urlInternalHost( 'www' );
  do_tests( 'www (1)', callback_urlInternal );
  
  $.urlInternalHost( 'foo' );
  do_tests( 'foo (2)', callback_urlInternal );
  
  $.urlInternalRegExp( /^(?:https?:)?\/\/(?:(?:www|foo)\.)?benalman.com\// );
  do_tests( 'regexp (3)', callback_urlInternal );
  
  do_tests( 'fragment (4)', callback_urlFragment );
});
<?
$shell['script'] = ob_get_contents();
ob_end_clean();

// ========================================================================== //
// HTML HEAD ADDITIONAL
// ========================================================================== //

ob_start();
?>
<script type="text/javascript" src="../../jquery.ba-urlinternal.js"></script>
<script type="text/javascript" language="javascript">

<?= $shell['script']; ?>

$(function(){
  
  // Syntax highlighter.
  SyntaxHighlighter.highlight();
  
});

</script>
<style type="text/css" title="text/css">

/*
bg: #FDEBDC
bg1: #FFD6AF
bg2: #FFAB59
orange: #FF7F00
brown: #913D00
lt. brown: #C4884F
*/

#test {
  width: 100%;
}

.header th {
  color: #fff;
  background: #999;
}

.meta td {
  background: #ddd;
  white-space: nowrap;
}

.int {
  color: #0a0;
}

.ext {
  color: #f00;
}

.frag {
  color: #00f;
}

.non {
  color: #ccc;
}

</style>
<?
$shell['html_head'] = ob_get_contents();
ob_end_clean();

// ========================================================================== //
// HTML BODY
// ========================================================================== //

ob_start();
?>

<p>
  <a href="/projects/jquery-urlinternal-plugin/">jQuery urlInternal</a> allows you to test whether any URL is internal or external, using an easily configurable RegExp. It can also test whether any URL is a fragment that will only change the location.hash, instead of navigating to a new page, even in IE6/7! Where would you use this? Here are a few basic examples, I'm sure you can come up with more:
</p>

<pre class="brush:js">
// Open every external link in a new window.
$("a:urlExternal").attr( "target", "_blank" );

// Pass document query string through to all internal links and forms (see jQuery BBQ at
// http://benalman.com/projects/jquery-bbq-plugin/ for the query string methods).
$("a, form").urlInternal().querystring( $.deparam.querystring() );

// Add an onclick handler to all fragment links (see jQuery BBQ for the pushState and fragment
// methods). In most browsers, you can do $("a[href^=#]") but that won't always work in IE6/7!
// Either make your selector more robust, or use :urlFragment!
$("a:urlFragment").click(function(){
  var frag = $.param.fragment( $(this).attr( 'href' ) );
  $.bbq.pushState({ page: frag });
  return false;
});
</pre>

<h3>Tests</h3>

<pre class="brush:js">
// For each of the example spans (at the bottom), test the URL in the "data-url" attribute.
$.elemUrlAttr({ span: 'data-url' });

// In tests 1-3, .urlInternal and .urlExternal are tested for the item in each table row,
// using each of these values.
$.urlInternalHost( "www" ); // 1
$.urlInternalHost( "foo" ); // 2
$.urlInternalRegExp( /^(?:https?:)?\/\/(?:(?:www|foo)\.)?benalman.com\// ); // 3

// In test 4, .urlFragment is tested for the item in each table rom.
</pre>

<h3>Results</h3>

<table id="test">
  <tr class="header"><th>relative links</th></tr>
  <tr class="meta"><td>element</td></tr>
  <tr><td><a href="./">./</a></td></tr>
  <tr><td><a href="../">../</a></td></tr>
  <tr><td><a href="?test=&a=1&a=2&b=boo">?test=&a=1&a=2&b=boo</a></td></tr>
  <tr><td><a href="foo">foo</a></td></tr>
  <tr><td><a href="foo#test-anchor">foo#test-anchor</a></td></tr>
  <tr><td><a href="foo/bar.html?baz=123">foo/bar.html?baz=123</a></td></tr>
  <tr><td><a href="bar.html?test=&a=1&a=2&b=boo">bar.html?test=&a=1&a=2&b=boo</a></td></tr>
  <tr><td><a href="/foo/">/foo/</a></td></tr>
  <tr><td><a href="/foo/bar.html?baz=123">/foo/bar.html?baz=123</a></td></tr>
  <tr><td><a href="/bar.html?test=&a=1&a=2&b=boo">/bar.html?test=&a=1&a=2&b=boo</a></td></tr>
  <tr><td><a href="foo#test-anchor">foo#test-anchor</a></td></tr>
  <tr><td><a href="/foo#test-anchor">/foo#test-anchor</a></td></tr>
  
  <tr class="header"><th>absolute links</th></tr>
  <tr class="meta"><td>element</td></tr>
  <tr><td><a href="//benalman.com/">//benalman.com/</a></td></tr>
  <tr><td><a href="//www.benalman.com/">//www.benalman.com/</a></td></tr>
  <tr><td><a href="//foo.benalman.com/">//foo.benalman.com/</a></td></tr>
  <tr><td><a href="http://benalman.com/">http://benalman.com/</a></td></tr>
  <tr><td><a href="http://benalman.com/foo/bar.html">http://benalman.com/foo/bar.html</a></td></tr>
  <tr><td><a href="http://benalman.com/bar.html?test=&a=1&a=2&b=boo">http://benalman.com/bar.html?test=&a=1&a=2&b=boo</a></td></tr>
  <tr><td><a href="http://www.benalman.com/">http://www.benalman.com/</a></td></tr>
  <tr><td><a href="http://www.benalman.com/foo/#test-anchor">http://www.benalman.com/foo/#test-anchor</a></td></tr>
  <tr><td><a href="http://foo.benalman.com/">http://foo.benalman.com/</a></td></tr>
  <tr><td><a href="http://foo.benalman.com/bar.html?test=&a=1&a=2&b=boo">http://foo.benalman.com/bar.html?test=&a=1&a=2&b=boo</a></td></tr>
  <tr><td><a href="https://foo.benalman.com/">https://foo.benalman.com/</a></td></tr>
  <tr><td><a href="https://benalman.com/">https://benalman.com/</a></td></tr>
  <tr><td><a href="https://www.benalman.com/">https://www.benalman.com/</a></td></tr>
  <tr><td><a href="http://bar.benalman.com/">http://bar.benalman.com/</a></td></tr>
  <tr><td><a href="http://benalman.com:81/">http://benalman.com:81/</a></td></tr>
  <tr><td><a href="http://bar.benalman.com/#foo">http://bar.benalman.com/#foo</a></td></tr>
  <tr><td><a href="http://benalman.com:81/#bar">http://benalman.com:81/#bar</a></td></tr>
  <tr><td><a href="http://google.com/">http://google.com/</a></td></tr>
  <tr><td><a href="http://google.com/bar.html?test=&a=1&a=2&b=boo">http://google.com/bar.html?test=&a=1&a=2&b=boo</a></td></tr>
  
  <tr class="header"><th>fragment links</th></tr>
  <tr class="meta"><td>element</td></tr>
  <tr><td><a href="#test-anchor">#test-anchor</a></td></tr>
  <tr><td><a href="./#test-anchor">./#test-anchor</a></td></tr>
  <tr><td><a href="../urlinternal/#test-anchor">../urlinternal/#test-anchor</a></td></tr>
  <tr><td><a href="#" id="fragment1">absolute path to this page plus a fragment</a></td></tr>
  <tr><td><a href="#" id="fragment2">/relative path to this page plus a fragment</a></td></tr>
  
  <tr class="header"><th>non-navigating links</th></tr>
  <tr class="meta"><td>element</td></tr>
  <tr><td><a href="mailto:spam@benalman.com">mailto:spam@benalman.com</a></td></tr>
  <tr><td><a href="javascript:alert('hello world')">javascript:alert('hello world')</a></td></tr>
  <tr><td><a href="ftp://ftp.example.com/foo/bar">ftp://ftp.example.com/foo/bar</a></td></tr>
  <tr><td><a href="irc://irc.example.com/foo/bar">irc://irc.example.com/foo/bar</a></td></tr>
  <tr><td><a href="arbitrary-scheme://example.com/foo/bar">arbitrary-scheme://example.com/foo/bar</a></td></tr>
  <tr><td><a href="arbitrary-scheme:example.com/foo/bar">arbitrary-scheme:example.com/foo/bar</a></td></tr>
  
  <tr class="header"><th>form</th></tr>
  <tr class="meta"><td>element</td></tr>
  <tr><td>
    <form action="?mode=test" method="post">
      &lt;form action="?mode=test" method="post"&gt;<br>
      <input type="submit" name="submit" value="Submit"/>
    </form>
  </td></tr>
  <tr><td>
    <form action="http://benalman.com/foo/?a=1&b=2" method="get">
      &lt;form action="http://benalman.com/foo/?a=1&b=2" method="get"&gt;<br>
      <input type="submit" name="submit" value="Submit"/>
    </form>
  </td></tr>
  <tr><td>
    <form action="http://www.benalman.com/foo/?a=1&b=2" method="get">
      &lt;form action="http://www.benalman.com/foo/?a=1&b=2" method="get"&gt;<br>
      <input type="submit" name="submit" value="Submit"/>
    </form>
  </td></tr>
  <tr><td>
    <form action="http://foo.benalman.com/bar/?a=1&b=2" method="get">
      &lt;form action="http://foo.benalman.com/bar/?a=1&b=2" method="get"&gt;<br>
      <input type="submit" name="submit" value="Submit"/>
    </form>
  </td></tr>
  <tr><td>
    <form action="https://benalman.com/foo/?a=1&b=2" method="get">
      &lt;form action="https://benalman.com/foo/?a=1&b=2" method="get"&gt;<br>
      <input type="submit" name="submit" value="Submit"/>
    </form>
  </td></tr>
  <tr><td>
    <form action="http://google.com/search?q=ben+alman" method="get">
      &lt;form action="http://google.com/search?q=ben+alman" method="get"&gt;<br>
      <input type="submit" name="submit" value="Submit"/>
    </form>
  </td></tr>
  
  <tr class="header"><th>span (using HTML5 "data-url" attribute)</th></tr>
  <tr class="meta"><td>element</td></tr>
  <tr><td>
    <span data-url="?mode=test">&lt;span data-url="?mode=test"&gt;</span>
  </td></tr>
  <tr><td>
    <span data-url="http://benalman.com/foo/?a=1&b=2">&lt;span data-url="http://benalman.com/foo/?a=1&b=2"&gt;</span>
  </td></tr>
  <tr><td>
    <span data-url="http://www.benalman.com/foo/?a=1&b=2">&lt;span data-url="http://www.benalman.com/foo/?a=1&b=2"&gt;</span>
  </td></tr>
  <tr><td>
    <span data-url="http://foo.benalman.com/bar/?a=1&b=2">&lt;span data-url="http://foo.benalman.com/bar/?a=1&b=2"&gt;</span>
  </td></tr>
  <tr><td>
    <span data-url="https://benalman.com/foo/?a=1&b=2">&lt;span data-url="https://benalman.com/foo/?a=1&b=2"&gt;</span>
  </td></tr>
  <tr><td>
    <span data-url="http://google.com/search?q=ben+alman">&lt;span data-url="http://google.com/search?q=ben+alman"&gt;</span>
  </td></tr>
</table>

<!--
<h3>The code</h3>

<pre class="brush:js">
<?= htmlspecialchars( $shell['script'] ); ?>
</pre>
-->

<?
$shell['html_body'] = ob_get_contents();
ob_end_clean();

// ========================================================================== //
// DRAW SHELL
// ========================================================================== //

draw_shell();

?>