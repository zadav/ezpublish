{cache-block}
{let node_list=fetch( content, list, hash( parent_node_id, $parent_node,
                      limit, 5,
					  sort_by, array( published, false() ) ) )}

<div class="toollist">
    <div class="toollist-design">
    <h2>{$title}</h2>
    <div class="content-view-children">
    {section var=node loop=$node_list sequence=array(bglight,bgdark)}
        {node_view_gui view=listitem content_node=$node}
    {/section}
    </div>
    </div>
</div>

{/let}
{/cache-block}