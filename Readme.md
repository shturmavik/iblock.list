# Description
  - component for list 0f bitrix
  - it based on news.list
  - requests less than news.list
  - set params in PROPERTY_CODE
  - add new fields in component.php if you want
  - in template properties call as PROPERTY_NAME_VALUE (name of property between PROPERTY_ and _VALUE)

```php
<? $APPLICATION->IncludeComponent(
    "myplace:iblock.list",
    ".default",
    [
        "ACTIVE_DATE_FORMAT"              => "d.m.Y",
        "ADD_SECTIONS_CHAIN"              => "N",
        "AJAX_MODE"                       => "N",
        "AJAX_OPTION_ADDITIONAL"          => "",
        "AJAX_OPTION_HISTORY"             => "N",
        "AJAX_OPTION_JUMP"                => "N",
        "AJAX_OPTION_STYLE"               => "N",
        "CACHE_FILTER"                    => "Y",
        "CACHE_GROUPS"                    => "N",
        "CACHE_TIME"                      => "86000",
        "CACHE_TYPE"                      => "A",
        "CHECK_DATES"                     => "Y",
        "COMPOSITE_FRAME_MODE"            => "A",
        "COMPOSITE_FRAME_TYPE"            => "AUTO",
        "DETAIL_URL"                      => "",
        "DISPLAY_BOTTOM_PAGER"            => "Y",
        "DISPLAY_DATE"                    => "Y",
        "DISPLAY_NAME"                    => "Y",
        "DISPLAY_PICTURE"                 => "Y",
        "DISPLAY_PREVIEW_TEXT"            => "Y",
        "DISPLAY_TOP_PAGER"               => "N",
        "FIELD_CODE"                      => ["", ""],
        "FILTER_NAME"                     => "arrFilter",
        "HIDE_LINK_WHEN_NO_DETAIL"        => "N",
        "IBLOCK_ID"                       => "1",
        "IBLOCK_TYPE"                     => "content",
        "INCLUDE_IBLOCK_INTO_CHAIN"       => "N",
        "INCLUDE_SUBSECTIONS"             => "Y",
        "MESSAGE_404"                     => "",
        "NEWS_COUNT"                      => "4",
        "PAGER_BASE_LINK"                 => "",
        "PAGER_BASE_LINK_ENABLE"          => "Y",
        "PAGER_DESC_NUMBERING"            => "Y",
        "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
        "PAGER_PARAMS_NAME"               => "arrPager",
        "PAGER_SHOW_ALL"                  => "N",
        "PAGER_SHOW_ALWAYS"               => "Y",
        "PAGER_TEMPLATE"                  => "",
        "PAGER_TITLE"                     => "Новости",
        "PARENT_SECTION"                  => "",
        "PARENT_SECTION_CODE"             => "",
        "PREVIEW_TRUNCATE_LEN"            => "",
        "PROPERTY_CODE"                   => [""],
        "SET_BROWSER_TITLE"               => "N",
        "SET_LAST_MODIFIED"               => "N",
        "SET_META_DESCRIPTION"            => "N",
        "SET_META_KEYWORDS"               => "N",
        "SET_STATUS_404"                  => "N",
        "SET_TITLE"                       => "N",
        "SHOW_404"                        => "N",
        "SORT_BY1"                        => "SORT",
        "SORT_BY2"                        => "SORT",
        "SORT_ORDER1"                     => "DESC",
        "SORT_ORDER2"                     => "ASC",
        "TOP_COUNT"                       => "4"
    ]
); ?>
```

License
----
MIT

**Free Software, Hell Yeah!**
