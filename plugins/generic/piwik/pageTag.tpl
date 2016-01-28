{**
 * pageTag.tpl
 *
 * Copyright (c) 2003-2008 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * Piwik page tag.
 *
 * $Id: pageTag.tpl,v 0.2.26 2008/07/02 00:05:04 vlilloh Exp $
 *}

 <!-- Piwik -->
<script type="text/javascript">
  var _paq = _paq || [];
  _paq.push(['trackPageView']);
  _paq.push(['enableLinkTracking']);
  (function() {ldelim}
    var u="{$piwikUrl}/";
    _paq.push(['setTrackerUrl', u+'piwik.php']);
    _paq.push(['setSiteId', {$piwikSiteId|escape}]);
    var d=document, g=d.createElement('script'), s=d.getElementsByTagName('script')[0];
    g.type='text/javascript'; g.async=true; g.defer=true; g.src=u+'piwik.js'; s.parentNode.insertBefore(g,s);
    {rdelim})();
</script>
<noscript><p><img src="{$piwikUrl}/piwik.php?idsite={$piwikSiteId|escape}" style="border:0;" alt="" /></p></noscript>
<!-- End Piwik Code -->