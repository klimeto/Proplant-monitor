'use strict';

define(['angular', 'ol', 'toolbar', 'layermanager', 'sidebar', 'map', 'ows', 'query', 'search', 'permalink', 'measure', 'legend', 'bootstrap', 'geolocation', 'core', 'api'],
 
    function(angular, ol, toolbar, layermanager) {
         var module = angular.module('hs', [
             'hs.sidebar',
             'hs.toolbar',
             'hs.layermanager',
            'hs.map',
             'hs.ows',
             'hs.query',
            'hs.search', 'hs.permalink','hs.measure',
            'hs.geolocation', 'hs.core',
            'hs.api'
         ]);
 
         module.directive('hs', ['hs.map.service', 'Core', function(OlMap, Core) {
             return {
                templateUrl: hsl_path + 'hslayers.html',
                link: function(scope, element) {
                    Core.fullScreenMap(element);
                }
            };
        }]);

        module.value('config', {
            box_layers: [
                new ol.layer.Group({
                    title: 'Base layers',
                    layers: [
                        new ol.layer.Tile({
                            source: new ol.source.OSM(),
                            title: "OpenStreetMap",
                            base: true,
                            visible: false,
                            path: 'Basemaps'
                        }),
						new ol.layer.Tile({
							title : "MapQuest Aerial",
							visible : false,
							source : new ol.source.MapQuest({
							layer : 'sat',
							}),
							path: 'Basemaps'
						}),
						new ol.layer.Tile({
							title : "MapQuest Hybrid",
							visible : false,
							source : new ol.source.MapQuest({
								layer : 'hyb'
							}),
							path: 'Basemaps'
						})
                        /*
						new ol.layer.Tile({
                            title: "CUZK ortophotomap",
                            source: new ol.source.TileWMS({
                                url: 'http://geoportal.cuzk.cz/WMS_ORTOFOTO_PUB/WMService.aspx?',
                                params: {
                                    LAYERS: 'GR_ORTFOTORGB',
                                    INFO_FORMAT: undefined,
                                    FORMAT: "image/png"
                                },
                                crossOrigin: null
                            }),
                            path: 'Basemaps',
                            visible: true,
                            opacity: 0.5
                        })
						*/
                        
                    ],
                }), new ol.layer.Group({
                    title: 'Source data layers',
                    layers: [
                        new ol.layer.Tile({
                            title: "CORINE 2006 to HILUCS map",
                            source: new ol.source.TileWMS({
                                url: 'http://gis.lesprojekt.cz/cgi-bin/mapserv?map=/home/dima/maps/olu/openlandusemap.map',
                                params: {
                                    LAYERS: 'corine',
                                    INFO_FORMAT: undefined,
                                    FORMAT: "image/png"
                                },
                                crossOrigin: null
                            }),
                            path: 'Source data',
                            visible: false
                        }),
                        new ol.layer.Tile({
                            title: "UrbanAtlas to HILUCS map",
                            source: new ol.source.TileWMS({
                                url: 'http://gis.lesprojekt.cz/cgi-bin/mapserv?map=/home/dima/maps/olu/openlandusemap.map',
                                params: {
                                    LAYERS: 'urbanatlas',
                                    INFO_FORMAT: undefined,
                                    FORMAT: "image/png"
                                },
                                crossOrigin: null
                            }),
                            path: 'Source data',
                            visible: false
                        }),
                        new ol.layer.Tile({
                            title: "LPIS to HILUCS map",
                            source: new ol.source.TileWMS({
                                url: 'http://gis.lesprojekt.cz/cgi-bin/mapserv?map=/home/dima/maps/olu/openlandusemap.map',
                                params: {
                                    LAYERS: 'lpis',
                                    INFO_FORMAT: undefined,
                                    FORMAT: "image/png"
                                },
                                crossOrigin: null
                            }),
                            path: 'Source data',
                            visible: false
                        })
                    ]
                }), new ol.layer.Group({
                    title: 'Composite Open-Land-Use Map',
                    layers: [
                        new ol.layer.Tile({
                            title: "Open-Land-Use Map - detailed (WMS)",
                            source: new ol.source.TileWMS({
                                url: 'http://gis.lesprojekt.cz/cgi-bin/mapserv?map=/home/dima/maps/olu/openlandusemap.map',
                                params: {
                                    LAYERS: 'detailed_olu',
                                    INFO_FORMAT: 'text/html',
                                    FORMAT: "image/png"
                                },
                                crossOrigin: null
                            }),
                            path: 'Open-Land-Use Map',
                            visible: true,
                            opacity: 0.5
                        }),
                        new ol.layer.Tile({
                            title: "Open-Land-Use Map - generalized and connected (WMS)",
                            source: new ol.source.TileWMS({
                                url: 'http://gis.lesprojekt.cz/cgi-bin/mapserv?map=/home/dima/maps/olu/openlandusemap.map',
                                params: {
                                    LAYERS: 'generalized_olu',
                                    INFO_FORMAT: undefined,
                                    FORMAT: "image/png"
                                },
                                crossOrigin: null
                            }),
                            path: 'Open-Land-Use Map',
                            visible: false,
                            opacity: 0.5
                        }),
                    ]
                }), 
            ],
            default_view: new ol.View({
                center: [1602959, 6353965], //Latitude longitude    to Spherical Mercator
                zoom: 15,
                units: "m"
            })
        }); 

        module.controller('Main', ['$scope', 'Core', 'hs.ows.wms.service_layer_producer', 'hs.query.service_infopanel', 'config',
            function($scope, Core, srv_producer, InfoPanelService, config) {
                if (console) console.log("Main called");
                $scope.hsl_path = hsl_path; //Get this from hslayers.js file
                $scope.Core = Core;
                $scope.Core.sidebarRight = false;
                $scope.Core.sidebarToggleable = false;
                $scope.Core.sidebarButtons = false;
                $scope.Core.setDefaultPanel('layermanager');
                $scope.$on('infopanel.updated', function(event) {
                    if (console) console.log('Attributes', InfoPanelService.attributes, 'Groups', InfoPanelService.groups);
                });
            }
        ]);

        return module;
    });