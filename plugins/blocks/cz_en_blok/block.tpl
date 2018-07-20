{**
 * plugins/blocks/cz_en_blok/block.tpl
 *
 * Copyright (c) 2013-2014 Simon Fraser University Library
 * Copyright (c) 2003-2014 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * Common site sidebar menu -- CZ EN toggle.
 *
 *}
{if $enableCz_en_blok}
<div class="block" id="sidebarCzEn">
	<script type="text/javascript">
		<!--
		function changeLanguageCzEn() {ldelim}
			var lang = document.getElementById('tlacitko');
                        
                        var new_locale = lang.value;
                        
			var base_url = "{$currentUrl|escape}";
			var current_url = document.URL;

			var redirect_url = '{url|escape:"javascript" page="user" op="setLocale" path="NEW_LOCALE" source=$smarty.server.REQUEST_URI}';
			redirect_url = redirect_url.replace("NEW_LOCALE", new_locale);
			window.location.href = redirect_url;
		{rdelim}
		//-->
	</script>
	<form action="#">
            {literal}
                <script type="text/javascript">
		<!--
                    if (document.getElementById('help_cz')){
                        document.write('<img src="https://journals.muni.cz/images/vlajky/cz_small_grey.png" class="lang_img" style="float:left;"/>');
                        document.write('<input id="tlacitko" type="submit" class="lang_cz_en en" value="en_US" onclick="changeLanguageCzEn(); return false;" />');
                    }else{
                        document.write('<input id="tlacitko" type="button" class="lang_cz_en cz" value="cs_CZ" onclick="changeLanguageCzEn(); return false;" />')
                        document.write('<img src="https://journals.muni.cz/images/vlajky/en_small_grey.png" class="lang_img_en"/>');
                    }
                 //-->
                 </script>
            {/literal}
                    
	</form>
</div>
{/if}
