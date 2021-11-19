<?

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}
/** @var CBitrixComponent $this */
/** @var array $arParams */
/** @var array $arResult */
/** @var string $componentPath */
/** @var string $componentName */
/** @var string $componentTemplate */
/** @global CDatabase $DB */
/** @global CUser $USER */
/** @global CMain $APPLICATION */
/** @global CIntranetToolbar $INTRANET_TOOLBAR */
global $INTRANET_TOOLBAR;

use Bitrix\Main\Loader;

if (!Loader::includeModule("iblock")) {
    die('Модуль инфоблоков не установлен');
}

if (!isset($arParams["CACHE_TIME"])) {
    $arParams["CACHE_TIME"] = 86000;
}

$arParams["IBLOCK_ID"] = trim($arParams["IBLOCK_ID"]);

if (!preg_match('/^(asc|desc|nulls)(,asc|,desc|,nulls){0,1}$/i', $arParams["SORT_ORDER1"])) {
    $arParams["SORT_ORDER1"] = "DESC";
}

if (strlen($arParams["FILTER_NAME"]) <= 0 || !preg_match("/^[A-Za-z_][A-Za-z01-9_]*$/", $arParams["FILTER_NAME"])) {
    $arFilter = [];
} else {
    $arFilter = $GLOBALS[$arParams["FILTER_NAME"]];
    if (!is_array($arFilter)) {
        $arFilter = [];
    }
}
if (!is_array($arParams["FIELD_CODE"])) {
    $arParams["FIELD_CODE"] = [];
}
foreach ($arParams["FIELD_CODE"] as $key => $val) {
    if (!$val) {
        unset($arParams["FIELD_CODE"][$key]);
    }
}

if (!is_array($arParams["PROPERTY_CODE"])) {
    $arParams["PROPERTY_CODE"] = [];
}
foreach ($arParams["PROPERTY_CODE"] as $key => $val) {
    if ($val === "") {
        unset($arParams["PROPERTY_CODE"][$key]);
    }
}

$arParams["DETAIL_URL"] = trim($arParams["DETAIL_URL"]);
$arParams["NEWS_COUNT"] = intval($arParams["NEWS_COUNT"]) > 0 ? intval($arParams["NEWS_COUNT"]) : '-1';
$arParams["ACTIVE_DATE_FORMAT"] = trim($arParams["ACTIVE_DATE_FORMAT"]);

if (strlen($arParams["ACTIVE_DATE_FORMAT"]) <= 0) {
    $arParams["ACTIVE_DATE_FORMAT"] = $DB->DateFormatToPHP(CSite::GetDateFormat("SHORT"));
}

$arFilterDate = [
    [
        "LOGIC"              => "OR",
        "<=DATE_ACTIVE_FROM" => $DB->FormatDate(
            date("Y-m-d H:i:s"),
            "YYYY-MM-DD HH:MI:SS",
            \CSite::GetDateFormat("FULL")
        ),
        "DATE_ACTIVE_FROM"   => false,
    ],
    [
        "LOGIC"            => "OR",
        ">=DATE_ACTIVE_TO" => $DB->FormatDate(
            date("Y-m-d H:i:s"),
            "YYYY-MM-DD HH:MI:SS",
            \CSite::GetDateFormat("FULL")
        ),
        "DATE_ACTIVE_TO"   => false,
    ]
];

$additionalCacheID = md5(json_encode(array_merge($arParams, $arFilter)));
if ($this->startResultCache($arParams['CACHE_TIME'], $additionalCacheID)) {
    //SELECT
    $arSelect = array_merge(
        $arParams["FIELD_CODE"],
        [
            "ID",
            "ACTIVE",
            "DATE_CREATE",
            "TIMESTAMP_X",
            "IBLOCK_ID",
            "DETAIL_PICTURE",
            "PREVIEW_PICTURE",
            "NAME",
            "DETAIL_TEXT",
            "DETAIL_PAGE_URL",
            "PREVIEW_TEXT",
        ]
    );

    foreach ($arParams["PROPERTY_CODE"] as $prop_name) {
        $arSelect[] = "PROPERTY_" . $prop_name;
    }

    $arFilter["IBLOCK_ID"] = $arParams["IBLOCK_ID"];
    $arFilter["IBLOCK_LID"] = SITE_ID;
    $arFilter["SECTION_ID"] = $arParams['PARENT_SECTION'];

    if (!!$arParams["ACTIVE"]) {
        $arFilter["ACTIVE"] = $arParams["ACTIVE"];
    }

    $arFilter = array_merge($arFilter, $arFilterDate);

    $arSort = [
        $arParams["SORT_BY1"] => $arParams["SORT_ORDER1"],
        $arParams["SORT_BY2"] => $arParams["SORT_ORDER2"]
    ];

    if ($arParams["NEWS_COUNT"] > 0) {
        $arNavParams["nTopCount"] = $arParams['NEWS_COUNT'];
    } else {
        $arNavParams = false;
    }

    //GETLIST
    $rsElement = CIBlockElement::GetList($arSort, $arFilter, false, $arNavParams, $arSelect);
    if (!$rsElement) {
        $this->abortResultCache();
    }

    $rsElement->SetUrlTemplates($arParams["DETAIL_URL"], "", $arParams["IBLOCK_URL"]);

    while ($arItem = $rsElement->GetNext()) {
        $arItem["PREVIEW_PICTURE"] = CFile::GetFileArray($arItem["PREVIEW_PICTURE"]);

        if (!empty($arItem["DETAIL_PICTURE"]) && in_array("DETAIL_PICTURE", $arParams["FIELD_CODE"])) {
            $arItem["DETAIL_PICTURE"] = CFile::GetFileArray($arItem["DETAIL_PICTURE"]);
        }

        if (strlen($arItem["ACTIVE_FROM"]) > 0) {
            $arItem["DISPLAY_ACTIVE_FROM"] = CIBlockFormatProperties::DateFormat(
                $arParams["ACTIVE_DATE_FORMAT"],
                MakeTimeStamp($arItem["ACTIVE_FROM"], CSite::GetDateFormat())
            );
        } else {
            $arItem["DISPLAY_ACTIVE_FROM"] = "";
        }

        $arResult["ITEMS"][] = $arItem;
    }

    $this->includeComponentTemplate();

    return !!$arResult["ITEMS"];
}//cache

