<?php
/*
 * @ PHP 5.6
 * @ Decoder version : 1.0.0.1
 */

if ($activePage !== "dashboard" && $activePage !== "settings") {
    echo " \r\n<nav class=\"cbp-spmenu cbp-spmenu-vertical cbp-spmenu-left\" id=\"cbp-spmenu-s1\">\r\n    <h3>Categories</h3>\r\n    <ul>\r\n      ";
    if (!empty($FinalCategoriesArray) && $FinalCategoriesArray["result"] == "success") {
        $TotalCountCategories = count($FinalCategoriesArray["data"]);
        $TotalCountForAll = 0;
        $ConditionCounter = 1;
        foreach ($FinalCategoriesArray["data"] as $catkey) {
            $OnloadActiveclass = "";
            if ($ConditionCounter == 1) {
                $OnloadActiveclass = "active onloadCallCategory";
            }
            $ConditionCounter += 1;
            echo "      <li>\r\n          <a onclick=\"";
            if (webtvpanel_parentcondition($catkey->category_name) == 1) {
                echo "confirmparent('";
                echo $catkey->category_id;
                echo "')";
            } else {
                echo "getData('";
                echo $catkey->category_id;
                echo "')";
            }
            echo "\" data-CategoryID=\"";
            echo $catkey->category_id;
            echo "\"  data-pcon=\"";
            echo webtvpanel_parentcondition($catkey->category_name);
            echo "\" class=\"";
            echo $OnloadActiveclass;
            echo "\">\r\n            ";
            echo $catkey->category_name != "other channels" ? $catkey->category_name : "Uncategorized";
            echo "          </a>\r\n      </li>\r\n    ";
        }
    }
    echo "    </ul>\r\n    <!-- <form class=\"sort\">\r\n      <label>Sort By:</label>\r\n      <div class=\"s-list\">\r\n        <select>\r\n          <option>Title</option>\r\n          <option>Year</option>\r\n          <option>Date added</option>\r\n          <option>Popularity</option>\r\n        </select>\r\n      </div>\r\n    </form> -->\r\n  </nav>\r\n  ";
}
echo "  <nav class=\"navbar navbar-inverse navbar-static-top\">\r\n    <div class=\"container full-container navb-fixid\">\r\n      ";
if ($activePage !== "dashboard" && $activePage !== "settings") {
    echo " \r\n      <div class=\"navbar-header\">\r\n        <div id=\"showLeft\" class=\"cat-toggle\"> <span></span> <span></span> <span></span> <span></span> </div>\r\n        <button type=\"button\" class=\"navbar-toggle collapsed pull-right\" data-toggle=\"offcanvas\"> <span class=\"sr-only\">Toggle navigation</span> <span class=\"icon-bar\"></span> <span class=\"icon-bar\"></span> <span class=\"icon-bar\"></span> </button>\r\n      </div>\r\n      ";
}
echo "      <a class=\"brand\" href=\"dashboard.php\"><img class=\"img-responsive\" src=\"";
echo isset($XClogoLinkval) && !empty($XClogoLinkval) ? $XClogoLinkval : "img/logo.png";
echo "\" alt=\"\" onerror=\"this.src='img/logo.png';\"></a>\r\n      <div id=\"navbar\" class=\"collapse navbar-collapse sidebar-offcanvas\">\r\n        ";
if ($activePage !== "index" && $activePage !== "dashboard") {
    echo "        <ul class=\"nav navbar-nav navbar-left main-icon\">\r\n        \t<li class=\"";
    if ($activePage == "index") {
        echo "active";
    }
    echo "\"><a href=\"dashboard.php\"><span class=\"da home\"></span><span>Home</span></a></li>\r\n\r\n          <li class=\"";
    if ($activePage == "live") {
        echo "active";
    }
    echo "\"><a href=\"live.php\"><span class=\"da live\"></span><span>Live Tv</span></a></li>          \r\n          <li class=\"";
    if ($activePage == "movies") {
        echo "active";
    }
    echo "\"><a href=\"movies.php\"><span class=\"da movie\"></span><span>Movies</span></a></li>\r\n          <li class=\"";
    if ($activePage == "series") {
        echo "active";
    }
    echo "\" ><a href=\"series.php\"><span class=\"da tv\"></span><span>Tv series</span></a></li>\r\n          <li class=\"";
    if ($activePage == "radio") {
        echo "active";
    }
    echo "\" ><a href=\"radio.php\"><span class=\"da radio\"></span><span>Radio</span></a></li>\r\n          <li class=\"";
    if ($activePage == "catchup") {
        echo "active";
    }
    echo "\"><a href=\"catchup.php\"><span class=\"da catchup\"></span><span>Catch Up</span></a></li>\r\n          <!-- <li class=\"";
    if ($activePage == "radio") {
        echo "active";
    }
    echo "\"><a href=\"radio.php\"><span class=\"da radio\"></span><span>Radio</span></a></li> -->\r\n          \r\n        </ul>\r\n        <ul class=\"nav navbar-nav navbar-right r-icon\">\r\n         ";
    if ($activePage !== "dashboard" && $activePage !== "settings") {
        echo " \r\n              <li class=\"r-li\"><a href=\"#search\"><i class=\"fa fa-search\"></i><span class=\"r-show\"></span></a></li>\r\n              <li class=\"r-li\"><a href=\"#sort\" data-toggle=\"modal\" data-target=\"#sortingpopup\"><i class=\"fa fa-sort\"></i><span class=\"r-show\"></span></a></li>\r\n\r\n             ";
    }
    echo "          <li class=\"r-li ";
    if ($activePage == "settings") {
        echo "active";
    }
    echo "\"><a href=\"settings.php\"><i class=\"fa fa-gear\"></i><span class=\"r-show\"></span></a></li>\r\n          ";
    if ($activePage !== "dashboard" && $activePage !== "settings") {
        echo " \r\n\r\n          <li class=\"r-li\"><a href=\"#\"\" class=\"logoutBtn\"><i class=\"fa fa-sign-out\"></i><span class=\"r-show\">Logout</span></a></li>\r\n        ";
    }
    echo "        </ul>\r\n      ";
} else {
    echo "          <ul class=\"nav navbar-nav navbar-right r-icon\">\r\n            <li><a href=\"settings.php\"><i class=\"fa fa-gear\"></i><span class=\"r-show\"></span></a></li>\r\n          </ul>\r\n          <p class=\"datetime\" style=\"margin-right: 20px;\"><span class=\"time\"></span>  <span class=\"date\"> ";
    echo date("d-M-Y");
    echo "</span></p>\r\n\r\n\r\n        ";
}
echo "      </div>\r\n      <!--/.nav-collapse --> \r\n    </div>\r\n  </nav>\r\n  <!-- Sorting Model -->\r\n  <style type=\"text/css\">\r\n    .sorting-container span {\r\n        font-size: 16px;\r\n        font-weight: 200;\r\n        cursor: pointer;\r\n    }\r\n  </style>\r\n<div class=\"modal fade\" id=\"sortingpopup\" role=\"dialog\" data-backdrop=\"static\" data-keyboard=\"false\" style=\"background: rgba(0, 0, 0, 0.9)\">\r\n    <div class=\"modal-dialog\">\r\n    \r\n      <!-- Modal content-->\r\n      ";
$sortCondition = isset($_COOKIE[$SessioStoredUsername . "_" . $activePage]) && !empty($_COOKIE[$SessioStoredUsername . "_" . $activePage]) ? $_COOKIE[$SessioStoredUsername . "_" . $activePage] : "default";
echo "      <div class=\"modal-content\">\r\n        <div class=\"modal-header\">\r\n          <button type=\"button\" class=\"close\" data-dismiss=\"modal\">&times;</button>\r\n          <h4 class=\"modal-title\">\r\n            <span>\r\n                <i class=\"fa fa-sort\" id=\"fappass\" aria-hidden=\"true\"></i>&nbsp;\r\n                Sort According to: \r\n              </span>\r\n          </h4>\r\n        </div>\r\n        <div class=\"modal-body\">\r\n          <div class=\"sorting-container\">\r\n            <label>\r\n              <input type=\"radio\" name=\"sorttype\" class=\"sorttype\" value=\"default\" ";
echo $sortCondition == "default" ? "checked" : "";
echo " > &nbsp;<span>Default</span>\r\n            </label>\r\n            <br>\r\n            <label>\r\n              <input type=\"radio\" name=\"sorttype\" class=\"sorttype\" value=\"topadded\" ";
echo $sortCondition == "topadded" ? "checked" : "";
echo " > &nbsp;<span>Top Added</span>\r\n            </label>\r\n            <br>\r\n            <label>\r\n              <input type=\"radio\" name=\"sorttype\" class=\"sorttype\" value=\"asc\" ";
echo $sortCondition == "asc" ? "checked" : "";
echo " > &nbsp;<span>A-Z</span>\r\n            </label>\r\n            <br>\r\n            <label>\r\n              <input type=\"radio\" name=\"sorttype\" class=\"sorttype\" value=\"desc\" ";
echo $sortCondition == "desc" ? "checked" : "";
echo " > &nbsp;<span>Z-A</span>\r\n            </label>\r\n          </div>            \r\n        </div>\r\n        <div class=\"modal-footer\">\r\n          <button type=\"button\" id=\"savesorting\" data-sortin=\"";
echo $activePage;
echo "\" class=\"btn btn-primary\">\r\n            Save \r\n            <i class=\"fa fa-spin fa-spinner hideOnLoad\" id=\"sortingloader\"></i>\r\n          </button>\r\n          <button type=\"button\" class=\"btn btn-default\" data-dismiss=\"modal\">Close</button>\r\n        </div>\r\n      </div>\r\n      \r\n    </div>\r\n  </div>\r\n\r\n  <script type=\"text/javascript\">\r\n    \$(document).ready(function(){\r\n      \$(\"#savesorting\").click(function(e){\r\n        e.preventDefault();\r\n        var SortIN = \$(this).data('sortin');\r\n        var selectedVal = \$(\"input:radio.sorttype:checked\").val();\r\n         \$('#sortingloader').removeClass('hideOnload');\r\n         jQuery.ajax({\r\n            type:\"POST\",\r\n            url:\"includes/ajax-control.php\",\r\n            dataType:\"text\",\r\n            data:{\r\n            action:'SaveSortSettings',\r\n            SortIN:SortIN,\r\n            selectedVal:selectedVal,\r\n            hostURL: \"";
echo $XCStreamHostUrl . $bar;
echo "\"\r\n            },\r\n            success:function(response2){ \r\n              \$('#sortingloader').addClass('hideOnload');\r\n                location.reload();\r\n            }\r\n         });\r\n      });\r\n    });\r\n  </script>";

?>