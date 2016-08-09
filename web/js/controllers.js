angularMap
    .controller('SetupController', function ($mdSidenav, $scope, $timeout, lodash, NgMap, Register, Travel) {
        var pusher = new Pusher('9c12e0599c4baa1f7367', {
            encrypted: true
        });

        var channel = pusher.subscribe('test_channel');
        channel.bind('my_event', function (data) {
            NgMap.getMap().then(function (map) {
                var marker_id = "mkr_" + data.id;
                map.markers[marker_id].setPosition({lat: data.latitude, lng: data.longitude});
            });
        });

        /*
         * Variables de $scope
         */
        $scope.isConfigurated = false;
        $scope.onRoad = false;
        $scope.showTravel = true;
        $scope.controlMap = false;
        $scope.setupMarkers = {
            "clients": {path: 'BACKWARD_CLOSED_ARROW', scale: 4},
            "drivers": {path: 'CIRCLE', scale: 5}
        };
        $scope.onSwipeLeftMenu = function () {
            $scope.toggleSidenav('configuration');
        };
        $scope.onSwipeRightMenu = function () {
            $scope.toggleSidenav('indications');
        };
        $scope.toggleSidenav = function (menuId) {
            $mdSidenav(menuId).toggle();
        };

        /*
         * View Model Section
         */
        var vm = this;
        vm.model = new Register();
        vm.total = {};
        vm.selected = {};
        vm.config = setupMap;
        vm.enabledGoTo = false;
        vm.location = {
            "latitude": "19.4340200",
            "longitude": "-99.1956010"
        };
        vm.positions = [];
        vm.travel = new Travel();
        vm.drivingMode = false;
        vm.drivingSpeed = 40;
        vm.driverMode = false;
        var updateFrequency = 1 * 1000;

        // Almacena la posicion y ayuda al salir del modo pausa
        var savedPath = null;

        // Overview path muestra la ruta entre origen y destino.
        // Esta no es la ruta que debemos seguir, este solo dibuja la ruta a seguir
        var overviewPath = [];
        var overviewPathIndex = 0;  // current index points of overview path

        // Camino detallado, es un overview de los puntos en la ruta.
        // ESTA ES LA RUTA QUE DEBEMOS SEGUIR
        var detailedPath = [];
        var detailedPathIndex = 0;  // Indice inicial

        var directionsService = new google.maps.DirectionsService();

        /**
         * Funciones de renderizado y persistencia
         */

        vm.selectPosition = function (event, entity) {
            vm.showDetails(entity);
            if (entity.type == 'driver') {
                vm.selected.origin = {driver_id: entity.id, position: this.getPosition()};
                Travel.getClient(vm.selected.origin, function (response) {
                    vm.selected.destination = {
                        client_id: response.data.id,
                        position: {lat: response.data.latitude, lng: response.data.longitude}
                    };
                    NgMap.getMap().then(function (map) {
                        vm.map = map;
                    });
                    vm.enabledGoTo = true;
                }, function (error) {

                });
            }
        };

        vm.showDetails = function (model) {
            NgMap.getMap().then(function (map) {
                vm.info = model;
                map.showInfoWindow('detailInfo', "mkr_" + model.id);
            });
        };

        vm.registerTravel = function () {
            Travel.save(vm.selected, function (response) {
                vm.travel = response.data;
                $scope.showTravel = false;
                $scope.controlMap = true;
                vm.goTo();
            });
        };

        vm.goTo = function () {
            vm.drivingMode = !vm.drivingMode;
            if (vm.drivingMode) {
                vm.map.setZoom(16);
                if (savedPath) { // if continues
                    driveDetailedPaths();
                } else {
                    driveOverviewPaths();
                }
            }
        };

        vm.drive = function () {
            vm.drivingMode = !vm.drivingMode;
            if (vm.drivingMode) {
                vm.map.setZoom(16);
                if (savedPath) { // if continues
                    driveDetailedPaths();
                } else {
                    driveOverviewPaths();
                }
            }
        };

        vm.directionsChanged = function () {
            overviewPath = this.directions.routes[0].overview_path;
            vm.map.getStreetView().setPosition(overviewPath[0]);

            overviewPathIndex = 0; // set indexes to 0s
            detailedPathIndex = 0;
            vm.drivingMode = false;   // stop driving
            toContinue = null;     // reset continuing positon
        };

        var driveOverviewPaths = function () {
            var op1, op2;
            // drive detailed path because we have not drove through all
            if (detailedPath.length > detailedPathIndex) {
                driveDetailedPaths(); //SHOW TIME !!!!
            }
            // drove all detailed path, get a new detailed path from overview paths
            else {
                op1 = overviewPath[overviewPathIndex];
                op2 = overviewPath[overviewPathIndex + 1];
                overviewPathIndex += 1;
                if (op1 && op2) {
                    var request = {origin: op1, destination: op2, travelMode: 'WALKING'};
                    directionsService.route(request, function (response, status) {
                        if (status == google.maps.DirectionsStatus.OK) {
                            detailedPath = response.routes[0].overview_path;
                            detailedPathIndex = 0;
                            driveOverviewPaths();
                        }
                    });
                }
            }
        };

        var driveDetailedPaths = function () {
            var meter = Math.floor(
                (parseInt(vm.drivingSpeed, 10) * 1000) / 3600  // how far we deive every second
                * (updateFrequency / 1000));                         // how often do we see streetview
            var point1 = detailedPath[detailedPathIndex];
            var point2 = detailedPath[detailedPathIndex + 1];

            if (point1 && point2) {
                //calculate where to look from two points
                var heading = google.maps.geometry.spherical.computeHeading(point1, point2);
                var distance = google.maps.geometry.spherical.computeDistanceBetween(point1, point2);
                var totalCount = parseInt(distance / meter, 10) || 1;

                var drive = function (count, position) {
                    if (totalCount >= count) {
                        $timeout(function () {
                            var pov = vm.map.getStreetView().getPov();
                            if (vm.driverMode) {
                                vm.map.setHeading(heading); // map heading is different from pov heading
                                pov.heading = heading;
                            }

                            vm.map.getStreetView().setPosition(position);
                            vm.map.getStreetView().setPov(pov);
                            vm.map.getStreetView().setVisible(true);

                            var distanceToPoint2 = google.maps.geometry.spherical.computeDistanceBetween(position, point2);
                            var nextPosition = distanceToPoint2 < meter ?
                                point2 : google.maps.geometry.spherical.computeOffset(position, meter, heading);
                            if (vm.drivingMode) {
                                drive(++count, nextPosition);
                            } else {
                                savedPath = {count: count, position: position};
                                return false;
                            }
                        }, updateFrequency);
                    } else {
                        detailedPathIndex += 1;
                        driveOverviewPaths();
                    }
                };

                var count = (savedPath && savedPath.count) || 1;
                var position = (savedPath && savedPath.position) || point1;
                savedPath = null; // once start driving, nullify savedPath
                drive(count, position);

            } else {
                detailedPathIndex += 1;
                driveOverviewPaths();
            }
        };

        function setupMap() {
            vm.model.$save(function (response) {
                $scope.isConfigurated = true;
                vm.positions = response.data;
            });
        }
    });
