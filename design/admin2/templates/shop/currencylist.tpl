{* DO NOT EDIT THIS FILE! Use an override template instead. *}
{if $show_error_message}
    <div class="{$error.style}">
        <h2><span class="time">[{currentdate()|l10n( shortdatetime )}]</span> {$error.description|wash}</h2>
    </div>
{/if}

<form name="currencylist" action={'shop/currencylist'|ezurl} method="post">

<div class="context-block">
{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">

<h1 class="context-title">{'Currencies'|i18n( 'design/admin/shop/currencylist' )}</h1>

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div></div></div></div></div></div>

{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">

{if $currency_list}
{* Items per page selector. *}
<div class="context-toolbar">
<div class="block">
<div class="left">
    <p>
    {switch match=$limit}

        {case match=25}
        <a href={'/user/preferences/set/currencies_list_limit/1'|ezurl} title="{'Show 10 items per page.'|i18n( 'design/admin/shop/currencylist' )}">10</a>
        <span class="current">25</span>
        <a href={'/user/preferences/set/currencies_list_limit/3'|ezurl} title="{'Show 50 items per page.'|i18n( 'design/admin/shop/currencylist' )}">50</a>
        {/case}

        {case match=50}
        <a href={'/user/preferences/set/currencies_list_limit/1'|ezurl} title="{'Show 10 items per page.'|i18n( 'design/admin/shop/currencylist' )}">10</a>
        <a href={'/user/preferences/set/currencies_list_limit/2'|ezurl} title="{'Show 25 items per page.'|i18n( 'design/admin/shop/currencylist' )}">25</a>
        <span class="current">50</span>
        {/case}

        {case}
        <span class="current">10</span>
        <a href={'/user/preferences/set/currencies_list_limit/2'|ezurl} title="{'Show 25 items per page.'|i18n( 'design/admin/shop/currencylist' )}">25</a>
        <a href={'/user/preferences/set/currencies_list_limit/3'|ezurl} title="{'Show 50 items per page.'|i18n( 'design/admin/shop/currencylist' )}">50</a>
        {/case}

        {/switch}
    </p>
</div>
<div class="break"></div>
</div>
</div>

<table class="list" cellspacing="0">
<tr>
    <th class="tight"><img src={'toggle-button-16x16.gif'|ezimage} alt="{'Invert selection.'|i18n( 'design/admin/shop/currencylist' )}" title="{'Invert selection.'|i18n( 'design/admin/shop/currencylist' )}" onclick="ezjs_toggleCheckboxes( document.currencylist, 'DeleteCurrencyList[]' ); return false;" /></th>
    <th class="name">{'Name'|i18n( 'design/admin/shop/currencylist' )}</th>
    <th class="class">{'Code'|i18n( 'design/admin/shop/currencylist' )}</th>
    <th class="class">{'Symbol'|i18n( 'design/admin/shop/currencylist' )}</th>
    <th class="class">{'Locale'|i18n( 'design/admin/shop/currencylist' )}</th>
    <th class="class">{'Status'|i18n( 'design/admin/shop/currencylist' )}</th>
    <th class="tight">{'Auto rate'|i18n( 'design/admin/shop/currencylist' )}</th>
    <th class="tight">{'Custom rate'|i18n( 'design/admin/shop/currencylist' )}</th>
    <th class="tight">{'Factor'|i18n( 'design/admin/shop/currencylist' )}</th>
    <th class="tight">{'Rate'|i18n( 'design/admin/shop/currencylist' )}</th>
    <th class="tight">&nbsp;</th>
</tr>

{if is_set( $currency_names )|not}
    {def $currency_names = hash()}
{/if}
{include uri='design:shop/currencynames.tpl'}

{def $auto_rate_value = ''}
{foreach $currency_list as $currency sequence array( bglight, bgdark ) as $bg_class_style}
    <tr class="{if eq( $currency.status, 2 ) }object-cannot-remove{else}{$bg_class_style}{/if}">
        <td><input type="checkbox" name="DeleteCurrencyList[]" value="{$currency.code}" title="{'Use these checkboxes to select items for removal. Click the "Remove selected" button to  remove the selected items.'|i18n( 'design/admin/shop/currencylist' )|wash()}" /></td>
        <td>
            {if is_set( $currency_names[$currency.code] )}
    	        {$currency_names[$currency.code]}
            {else}
                {'Unknown currency name'|i18n( 'design/admin/shop/currencylist' )}
            {/if}
        </td>
        <td>{$currency.code}</td>
        <td>{$currency.symbol}</td>
        <td>{$currency.locale}</td>
        <td><select name="CurrencyList[{$currency.code}][status]" title="{'Select status'|i18n( 'design/admin/shop/currencylist' )}">
                <option value="active" {if eq($currency.status, 1)}selected = "selected"{/if} >Active</option>
                <option value="inactive" {if eq($currency.status, 2)}selected = "selected"{/if}>Inactive</option>
            </select>
        </td>

        {if gt($currency.auto_rate_value, 0)}
            {set auto_rate_value = $currency.auto_rate_value}
        {else}
            {set auto_rate_value = 'N/A'|i18n( 'design/admin/shop/currencylist' )}
        {/if}

        {if gt($currency.custom_rate_value, 0)}
            <td class="na-rate">{$auto_rate_value}</td>
        {else}
            <td class="number">{$auto_rate_value}</td>
        {/if}

        <td class="number"><input type="text" size="10" name="CurrencyList[{$currency.code}][custom_rate_value]" value="{if gt($currency.custom_rate_value, 0)}{$currency.custom_rate_value}{else}{/if}" /></td>
        <td class="number"><input type="text" size="10" name="CurrencyList[{$currency.code}][rate_factor]" value="{$currency.rate_factor}" /></td>
        {if gt($currency.rate_value, 0)}
            <td class="number">{$currency.rate_value}</td>
        {else}
            <td class="na-rate">{'N/A'|i18n( 'design/admin/shop/currencylist' )}</td>
        {/if}
        <td><a href={concat( 'shop/editcurrency/(currency)/', $currency.code)|ezurl}><img src={'edit.gif'|ezimage} alt="{'Edit'|i18n( 'design/admin/shop/currencylist' )}" title="{"Edit '%currency_code' currency."|i18n( 'design/admin/shop/currencylist',, hash( '%currency_code', $currency.code ) )|wash}" /></a></td>
    </tr>

{/foreach}
{undef $auto_rate_value}
</table>

<div class="context-toolbar">
{include name=navigator
         uri='design:navigator/google.tpl'
         page_uri='/shop/currencylist'
         item_count=$currency_list_count
         view_parameters=$view_parameters
         item_limit=$limit}
</div>

{else}
    <div class="block">
    <p>{'The available currency list is empty'|i18n( 'design/admin/shop/currencylist' )}</p>
    </div>
{/if}
{* DESIGN: Content END *}</div></div></div>

{* Button bar for remove and add currency. *}
<div class="controlbar">

{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-tc"><div class="box-bl"><div class="box-br">

<div class="block">
    {if $currency_list}
        <div class="left">
            {* Remove button *}
            <input class="button" type="submit" name="RemoveCurrencyButton" value="{'Remove selected'|i18n( 'design/admin/shop/currencylist' )}" title="{'Remove selected currencies from the list above.'|i18n( 'design/admin/shop/currencylist' )}" />
            {* New button *}
            <input class="button" type="submit" name="NewCurrencyButton" value="{'New currency'|i18n( 'design/admin/shop/currencylist' )}" title="{'Add new currency to the list above.'|i18n( 'design/admin/shop/currencylist' )}" />
        </div>
        <div class="right">
            {* Update auto rates button *}
            {def $exchangeRatesUpdateHandler = ezini( 'ExchangeRatesSettings', 'ExchangeRatesUpdateHandler', 'shop.ini' )}
            {if $exchangeRatesUpdateHandler}
                <input class="button" type="submit" name="UpdateAutoRatesButton" value="{'Update auto rates'|i18n( 'design/admin/shop/currencylist' )}" title="{'Update auto rates.'|i18n( 'design/admin/shop/currencylist' )}" />
            {else}
                <input class="button-disabled" type="submit" disabled="disabled" name="UpdateAutoRatesButton" value="{'Update auto rates'|i18n( 'design/admin/shop/currencylist' )}" title="{'Update auto rates.'|i18n( 'design/admin/shop/currencylist' )}" />
            {/if}
            {* Update autoprices button *}
            <input class="button" type="submit" name="UpdateAutopricesButton" value="{'Update autoprices'|i18n( 'design/admin/shop/currencylist' )}" title="{'Update autoprices.'|i18n( 'design/admin/shop/currencylist' )}" />

            {* Apply changes button *}
            <input class="button" type="submit" name="ApplyChangesButton" value="{'Apply changes'|i18n( 'design/admin/shop/currencylist' )}" title="{'Apply statuses, custom rates, factor values.'|i18n( 'design/admin/shop/currencylist' )}" />
        </div>
    {else}
        <div class="left">
            {* New button *}
            <input class="button" type="submit" name="NewCurrencyButton" value="{'New currency'|i18n( 'design/admin/shop/currencylist' )}" title="{'Add new currency to the list above.'|i18n( 'design/admin/shop/currencylist' )}" />
        </div>
    {/if}

    <div class="break"></div>
</div>
{* DESIGN: Control bar END *}</div></div></div></div></div></div>

</div>
</div>

<input type="hidden" name="Offset" value="{$view_parameters.offset}" />

</form>