jQuery(document).ready(function ($) {
    'use strict';
    var loc, map;

    function initMap(bing_map_zoom_level, loc) {
        if ($('#woocommerce-thank-you-page-bing-map').length > 0) {
            map = new Microsoft.Maps.Map(document.getElementById('woocommerce-thank-you-page-bing-map'), {
                center: new Microsoft.Maps.Location(loc[0], loc[1]),
                mapTypeId: get_map_style(wtyp_front_end_bing_map_params.bing_map_view),
                zoom: bing_map_zoom_level,
                navigationBarMode: get_map_nav_bar_mode(wtyp_front_end_bing_map_params.bing_map_navbarmode),
            });
            set_bing_map(map, loc);
        }
    }

    function set_bing_map(map, loc) {
        var map_center = map.getCenter();
        var map_infobox = new Microsoft.Maps.Infobox(map_center, {
            visible: false
        });
        map_infobox.setMap(map);
        let map_custom;
        if (wtyp_front_end_bing_map_params.bing_map_marker) {
            map_custom = new Microsoft.Maps.Pushpin(new Microsoft.Maps.Location(loc[0], loc[1]), {
                icon: wtyp_front_end_bing_map_params.bing_map_marker,
                title: wtyp_front_end_bing_map_params.bing_map_address,
                description: wtyp_front_end_bing_map_params.bing_map_label.replace(/\n/g, '<\/br>')
            });
        } else {
            map_custom = new Microsoft.Maps.Pushpin(new Microsoft.Maps.Location(loc[0], loc[1]), {});
        }

        map.entities.push(map_custom);
    }

    function get_map_nav_bar_mode(mode) {
        let nav_mode;
        switch (mode) {
            case 'compact':
                nav_mode = Microsoft.Maps.NavigationBarMode.compact;
                break;
            case 'minified':
                nav_mode = Microsoft.Maps.NavigationBarMode.minified;
                break;
            default:
                nav_mode = Microsoft.Maps.NavigationBarMode.default;
                break;
        }
        return nav_mode;
    }

    function get_map_style(bing_map_view) {
        let map_style;
        switch (bing_map_view) {
            case 'aerial':
                map_style = Microsoft.Maps.MapTypeId.aerial;
                break;
            case 'canvasDark':
                map_style = Microsoft.Maps.MapTypeId.canvasDark;
                break;
            case 'canvasLight':
                map_style = Microsoft.Maps.MapTypeId.canvasLight;
                break;
            case 'grayscale':
                map_style = Microsoft.Maps.MapTypeId.grayscale;
                break;
            default:
                map_style = Microsoft.Maps.MapTypeId.road;
        }
        return map_style;
    }

    function initBingMap() {
        if (!loc) {
            $.ajax({
                url: 'https://dev.virtualearth.net/REST/v1/Locations',
                type: 'GET',
                dataType: "jsonp",
                jsonp: "jsonp",
                data: {
                    query: wtyp_front_end_bing_map_params.bing_map_address,
                    key: wtyp_front_end_bing_map_params.bing_map_api,
                },
                beforeSend: function () {
                },
                success: function (result) {
                    loc = result.resourceSets[0].resources[0].point.coordinates;
                    initMap(parseInt(wtyp_front_end_bing_map_params.bing_map_zoom_level), loc);
                },
                error: function (err) {
                    console.log(err);
                }
            });
        } else {
            initMap(parseInt(wtyp_front_end_bing_map_params.bing_map_zoom_level), loc);
        }
    }

    window.addEventListener('load', function () {
        initBingMap();
    });

});
