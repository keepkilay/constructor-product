<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */


$arSelect = Array("ID", "IBLOCK_ID", "NAME", "PROPERTY_*");
$arFilter = Array("IBLOCK_ID" => getIblockIdByCode("blocks-products"), "ID" => $arResult["PROPERTIES"]["CONSTRACT"]["VALUE"],  "ACTIVE_DATE" => "Y", "ACTIVE" => "Y");
$res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
while ($ob = $res->GetNextElement()) {
    $arFields = $ob->GetFields();
    $arFields["PROPERTIES"] = $ob->GetProperties();

    $arResult["BLOCKS_LIST"][$arFields["ID"]] = $arFields;
}
foreach ($arResult["PROPERTIES"]["CONSTRACT"]["VALUE"] as $id){
    $arResult["BLOCKS"][] = $arResult["BLOCKS_LIST"][$id];
}


$arSelect = Array("ID", "IBLOCK_ID", "NAME", "PROPERTY_CONSTRACT");
$arFilter = Array("IBLOCK_ID" => $arParams["IBLOCK_ID"], "ACTIVE_DATE" => "Y", "ACTIVE" => "Y");
$res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
while ($ob = $res->GetNextElement()) {
    $arFields = $ob->GetFields();
    $properties = $ob->GetProperties();

    if (isset($properties["CONSTRACT"])) {
        $arResult["PROP_CONSTRACTS"][$arFields["ID"]] = $properties["CONSTRACT"]["VALUE"];
    }

}



