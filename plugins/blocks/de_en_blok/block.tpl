{**
 * plugins/blocks/de_en_blok/block.tpl
 *
 * Copyright (c) 2013-2014 Simon Fraser University Library
 * Copyright (c) 2003-2014 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * Common site sidebar menu -- DE EN toggle.
 *
 *}
{if $enableDe_en_blok}
<div class="block" id="sidebarDeEn">
	<script type="text/javascript">
		
		function changeLanguageDeEn() {ldelim}
			var lang = document.getElementById('tlacitko');
                        
                        var new_locale = lang.value;
                        
			var base_url = "{$currentUrl|escape}";
			var current_url = document.URL;

			var redirect_url = '{url|escape:"javascript" page="user" op="setLocale" path="NEW_LOCALE" source=$smarty.server.REQUEST_URI}';
			redirect_url = redirect_url.replace("NEW_LOCALE", new_locale);
			window.location.href = redirect_url;
		{rdelim}
		
	</script>
	<form action="#">
            {literal}
                <script type="text/javascript">
		
                    if (document.getElementById('help_de')){
                        document.write('<img src="https://journals.muni.cz/images/vlajky/de_small_grey.png" class="lang_img" style="float:left;"/>');
                        document.write('<input id="tlacitko" type="submit" class="lang_cz_en en" value="en_US" onclick="changeLanguageDeEn(); return false;" />');
                    }elseif (document.getElementById('help_en')){
                        document.write('<input id="tlacitko" type="button" class="lang_cz_en de" value="de_DE" onclick="changeLanguageDeEn(); return false;" />')
                        document.write('<img src="https://journals.muni.cz/images/vlajky/en_small_grey.png" class="lang_img_en"/>');
                    }
                 
                 </script>
            {/literal}
                    
	</form>
</div>
{/if}
