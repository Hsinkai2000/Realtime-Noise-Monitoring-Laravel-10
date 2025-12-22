<!DOCTYPE html>
<html>

<head>
    <script>
        function subst() {
            var vars = {};
            var query_strings_from_url = document.location.search.substring(1).split('&');
            for (var query_string in query_strings_from_url) {
                if (query_strings_from_url.hasOwnProperty(query_string)) {
                    var temp_var = query_strings_from_url[query_string].split('=', 2);
                    vars[temp_var[0]] = decodeURI(temp_var[1]);
                }
            }
            var css_selector_classes = ['page', 'frompage', 'topage', 'webpage', 'section', 'subsection', 'date', 'isodate',
                'time', 'title', 'doctitle', 'sitepage', 'sitepages'
            ];
            for (var css_class in css_selector_classes) {
                if (css_selector_classes.hasOwnProperty(css_class)) {
                    var element = document.getElementsByClassName(css_selector_classes[css_class]);
                    for (var j = 0; j < element.length; ++j) {
                        element[j].textContent = vars[css_selector_classes[css_class]];
                    }
                }
            }
        }
    </script>
    <style>
        html {
            -webkit-print-color-adjust: exact;
        }

        .left {
            padding-left: 20px;
        }

        .right {
            padding-right: 20px;
        }

        .footer {
            background-color: #bd6747;
            width: 100%;
            border-radius: 10px 10px 0px 0px;
        }

        .bottom-section {
            display: flex;
            display: -webkit-box;
            /* wkhtmltopdf uses this one */
            flex-direction: row;
            color: white;
            width: 100%;
            -webkit-box-pack: justify;
            /* wkhtmltopdf uses this one */
            justify-content: space-between;
            -webkit-box-align: center;
            align-content: center;
            height: 40px;
        }
    </style>
</head>


<body onload="subst()">
    <div class="footer">
        <div class="bottom-section">
            <div class="left">Geoscan Data Tracking System</div>
            <div class="right"><span class="page"></span> of <span class="topage"></span></div>
        </div>
    </div>
</body>

</html>
