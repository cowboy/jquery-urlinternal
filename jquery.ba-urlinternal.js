/*!
 * urlInternal - v1.0pre - 10/4/2009
 * http://benalman.com/projects/jquery-bbq-plugin/
 * 
 * Copyright (c) 2009 "Cowboy" Ben Alman
 * Licensed under the MIT license
 * http://benalman.com/about/license/
 */

// Script: jQuery urlInternal: Easily test URL internal- or external-ness
// 
// *Version: 1.0pre, Last updated: 10/4/2009*
// 
// Project Home - http://benalman.com/projects/jquery-urlinternal-plugin/
// GitHub       - http://github.com/cowboy/jquery-urlinternal/
// Source       - http://github.com/cowboy/jquery-urlinternal/raw/master/jquery.ba-urlinternal.js
// (Minified)   - http://github.com/cowboy/jquery-urlinternal/raw/master/jquery.ba-urlinternal.min.js (1.3kb)
// 
// About: License
// 
// Copyright (c) 2009 "Cowboy" Ben Alman,
// Licensed under the MIT license.
// http://benalman.com/about/license/
// 
// About: Examples
// 
// These working examples, complete with fully commented code, illustrate a few
// ways in which this plugin can be used.
// 
// COMING SOON
// 
// About: Support and Testing
// 
// Information about what version or versions of jQuery this plugin has been
// tested with, what browsers it has been tested in, and where the unit tests
// reside (so you can test it yourself).
// 
// jQuery Versions - 1.3.2
// Browsers Tested - Internet Explorer 6-8, Firefox 2-3.7, Safari 3-4, Chrome, Opera 9.6-10.
// Unit Tests      - http://benalman.com/code/projects/jquery-urlinternal/unit/
// 
// About: Release History
// 
// 1.0pre - (10/4/2009) Pre-Initial release

(function($){
  '$:nomunge'; // Used by YUI compressor.
  
  // Some convenient shortcuts.
  var undefined,
    TRUE = !0,
    FALSE = !1,
    loc = document.location,
    
    // Method references.
    jq_elemUrlAttr,
    jq_urlInternalHost,
    jq_urlInternalRegExp,
    jq_isUrlInternal,
    jq_isUrlExternal,
    
    // Reused strings.
    str_elemUrlAttr = 'elemUrlAttr',
    str_href = 'href',
    str_src = 'src',
    str_urlInternal = 'urlInternal',
    str_urlExternal = 'urlExternal',
    
    url_regexp,
    
    // Used by jQuery.elemUrlAttr.
    elemUrlAttr_cache = {};
  
  // Section: Methods
  // 
  // Method: jQuery.isUrlInternal
  // 
  // Test whether or not a URL is internal. Non-navigating URLs (ie. #anchor,
  // javascript:, mailto:, news:, tel:, im: or non-http/https protocol://
  // links) are not considered internal.
  // 
  // Usage:
  // 
  // > jQuery.isUrlInternal( url );
  // 
  // Arguments:
  // 
  //   url - (String) a URL to test the internal-ness of.
  // 
  // Returns:
  // 
  //  (Boolean) true if the URL is internal, false if external, or undefined if
  //  the URL is non-navigating.
  
  $.isUrlInternal = jq_isUrlInternal = function( url ) {
    
    // non-navigating: url is nonexistent
    if ( !url ) { return undefined; }
    
    // internal: url is absolute-but-internal (see $.urlInternalRegExp)
    if ( url_regexp.test(url) ) { return TRUE; }
    
    // external: url is absolute (begins with http:// or https:// or //)
    if ( /^(?:https?:)?\/\//i.test(url) ) { return FALSE; }
    
    // non-navigating: url begins with # or scheme:
    if ( /^(?:#|[a-z\d.-]+:)/i.test(url) ) { return undefined; }
    
    return TRUE;
  };
  
  // Method: jQuery.isUrlExternal
  // 
  // Test whether or not a URL is external. Non-navigating URLs (ie. #anchor,
  // mailto:, javascript:, or non-http/https protocol:// links) are not
  // considered external.
  // 
  // Usage:
  // 
  // > jQuery.isUrlExternal( url );
  // 
  // Arguments:
  // 
  //   url - (String) a URL to test the external-ness of.
  // 
  // Returns:
  // 
  //  (Boolean) true if the URL is external, false if internal, or undefined if
  //  the URL is non-navigating.
  
  $.isUrlExternal = jq_isUrlExternal = function( url ) {
    var result = jq_isUrlInternal( url );
    
    return typeof result === 'boolean'
      ? !result
      : result;
  };
  
  // Method: jQuery.fn.urlInternal
  // 
  // Filter a jQuery collection of elements, returning only elements that have
  // an internal URL (as determined by <jQuery.isUrlInternal>). If URL cannot
  // be determined, remove the element from the collection.
  // 
  // Usage:
  // 
  // > jQuery('selector').urlInternal( [ attr ] );
  // 
  // Arguments:
  // 
  //  attr - (String) Optional name of an attribute that will contain a URL to
  //    test internal-ness against. See <jQuery.elemUrlAttr> for a list of
  //    default attributes.
  // 
  // Returns:
  // 
  //  (jQuery) A filtered jQuery collection of elements.
  
  // Method: jQuery.fn.urlExternal
  // 
  // Filter a jQuery collection of elements, returning only elements that have
  // an external URL (as determined by <jQuery.isUrlExternal>). If URL cannot
  // be determined, remove the element from the collection.
  // 
  // Usage:
  // 
  // > jQuery('selector').urlExternal( [ attr ] );
  // 
  // Arguments:
  // 
  //  attr - (String) Optional name of an attribute that will contain a URL to
  //    test external-ness against. See <jQuery.elemUrlAttr> for a list of
  //    default attributes.
  // 
  // Returns:
  // 
  //  (jQuery) A filtered jQuery collection of elements.
  
  $.fn[str_urlInternal] = function( attr ) {
    return this.filter( ':' + str_urlInternal + (attr ? '(' + attr + ')' : '') );
  };

  $.fn[str_urlExternal] = function( attr ) {
    return this.filter( ':' + str_urlExternal + (attr ? '(' + attr + ')' : '') );
  };
  
  // Section: Selectors
  // 
  // Selector: :urlInternal
  // 
  // Filter a jQuery collection of elements, returning only elements that have
  // an internal URL (as determined by <jQuery.isUrlInternal>). If URL cannot
  // be determined, remove the element from the collection.
  // 
  // Usage:
  // 
  // > jQuery('selector').filter(':urlInternal');
  // > jQuery('selector').filter(':urlInternal(attr)');
  // 
  // Arguments:
  // 
  //  attr - (String) Optional name of an attribute that will contain a URL to
  //    test internal-ness against. See <jQuery.elemUrlAttr> for a list of
  //    default attributes.
  // 
  // Returns:
  // 
  //  (jQuery) A filtered jQuery collection of elements.
  
  // Selector: :urlExternal
  // 
  // Filter a jQuery collection of elements, returning only elements that have
  // an external URL (as determined by <jQuery.isUrlExternal>). If URL cannot
  // be determined, remove the element from the collection.
  // 
  // Usage:
  // 
  // > jQuery('selector').filter(':urlExternal');
  // > jQuery('selector').filter(':urlExternal(attr)');
  // 
  // Arguments:
  // 
  //  attr - (String) Optional name of an attribute that will contain a URL to
  //    test external-ness against. See <jQuery.elemUrlAttr> for a list of
  //    default attributes.
  // 
  // Returns:
  // 
  //  (jQuery) A filtered jQuery collection of elements.
  
  function get_attr( match, elem ) {
    return match[3] || jq_elemUrlAttr()[ ( elem.nodeName || '' ).toLowerCase() ] || '';
  };
  
  $.expr[':'][str_urlInternal] = function( elem, i, match ) {
    var a = get_attr( match, elem );
    
    return a ? !!jq_isUrlInternal( $(elem).attr(a) ) : FALSE;
  };
  
  $.expr[':'][str_urlExternal] = function( elem, i, match ) {
    var a = get_attr( match, elem );
    
    return a ? !!jq_isUrlExternal( $(elem).attr(a) ) : FALSE;
  };
  
  // Section: Support methods
  // 
  // Method: jQuery.elemUrlAttr
  // 
  // Get the internal "Default URL attribute per tag" list, or augment the list
  // with additional tag-attribute pairs, in case the defaults are insufficient.
  // 
  // In the <jQuery.fn.urlInternal> and <jQuery.fn.urlExternal> methods, as well
  // as the <:urlInternal> and <:urlExternal> selectors, this list is used to
  // determine which attribute contains the URL to be modified, if an "attr"
  // param is not specified.
  // 
  // Default Tag-Attribute List:
  // 
  //  a      - href
  //  base   - href
  //  iframe - src
  //  img    - src
  //  input  - src
  //  form   - action
  //  link   - href
  //  script - src
  // 
  // Usage:
  // 
  // > jQuery.elemUrlAttr( [ tag_attr ] );
  // 
  // Arguments:
  // 
  //  tag_attr - (Object) An object containing a list of tag names and their
  //    associated default attribute names in the format { tag: 'attr', ... } to
  //    be merged into the internal tag-attribute list.
  // 
  // Returns:
  // 
  //  (Object) An object containing all stored tag-attribute values.
  
  // Only define function and set defaults if function doesn't already exist, as
  // the jQuery BBQ plugin will provide this method as well.
  $[ str_elemUrlAttr ] || ($[ str_elemUrlAttr ] = function( obj ) {
    return $.extend( elemUrlAttr_cache, obj );
  })({
    a: str_href,
    base: str_href,
    iframe: str_src,
    img: str_src,
    input: str_src,
    form: 'action',
    link: str_href,
    script: str_src
  });
  
  jq_elemUrlAttr = $[ str_elemUrlAttr ];
  
  // Method: jQuery.urlInternalHost
  // 
  // Constructs the regular expression that matches an absolute-but-internal
  // URL from the current page's protocol, hostname and port, allowing for any
  // number of optional hostnames. For example, if the current page is
  // http://benalman.com/test or http://www.benalman.com/test, specifying an
  // argument of "www" would yield this pattern:
  // 
  // > /^(?:http:)?\/\/(?:(?:www)\.)?benalman.com\//i
  // 
  // This pattern will match URLs beginning with both http://benalman.com/ and
  // http://www.benalman.com/. If the current page is http://benalman.com/test,
  // http://www.benalman.com/test or http://foo.benalman.com/test, specifying
  // arguments "www", "foo" would yield this pattern:
  // 
  // > /^(?:http:)?\/\/(?:(?:www|foo)\.)?benalman.com\//i
  // 
  // This pattern will match URLs beginning with http://benalman.com/,
  // http://www.benalman.com/ and http://foo.benalman.com/.
  // 
  // Not specifying any alt_hostname will disable any alt-hostname matching.
  // 
  // Note that the plugin is initialized by default to an alt_hostname of "www".
  // Should you need more control, <jQuery.urlInternalRegExp> may be used to
  // completely customize the absolute-but-internal matching pattern.
  // 
  // Usage:
  // 
  // > jQuery.urlInternalHost( [ alt_hostname [, alt_hostname ] ... ] );
  // 
  // Arguments:
  // 
  //  alt_hostname - (String) An optional alternate hostname to use when testing
  //    URL absolute-but-internal-ness. 
  // 
  // Returns:
  // 
  //  (RegExp) The absolute-but-internal pattern, as a RegExp.
  
  $.urlInternalHost = jq_urlInternalHost = function( alt_hostname ) {
    alt_hostname = alt_hostname
      ? '(?:(?:' + Array.prototype.join.call( arguments, '|' ) + ')\\.)?'
      : '';
    
    var re = new RegExp( '^' + alt_hostname + '(.*)', 'i' ),
      pattern = '^(?:' + loc.protocol + ')?//'
        + loc.hostname.replace(re, alt_hostname + '$1').replace( /\\?\./g, '\\.' )
        + (loc.port ? ':' + loc.port : '') + '/';
    
    console.log( pattern );
    return jq_urlInternalRegExp( pattern );
  };
    
  // Method: jQuery.urlInternalRegExp
  // 
  // Set or get the regular expression that matches an absolute-but-internal
  // URL.
  // 
  // Usage:
  // 
  // > jQuery.urlInternalRegExp( [ re ] );
  // 
  // Arguments:
  // 
  //  re - (String or RegExp) The regular expression pattern. If not passed,
  //    nothing is changed.
  // 
  // Returns:
  // 
  //  (RegExp) The absolute-but-internal pattern, as a RegExp.
  
  $.urlInternalRegExp = jq_urlInternalRegExp = function( re ) {
    if ( re ) {
      url_regexp = typeof re === 'string'
        ? new RegExp( re, 'i' )
        : re;
    }
    
    return url_regexp;
  };
  
  // Initialize url_regexp with a reasonable default.
  jq_urlInternalHost( 'www' );
  
})(jQuery);