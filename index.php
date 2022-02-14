<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Explore Japan</title>
	
	<link rel="stylesheet" href="resources/css/bootstrap.min.css" />
    <link rel="stylesheet" href="resources/css/stylesheet.css" />
    <link rel="stylesheet" href="resources/css/sakura.css" />
    <!--link rel="stylesheet" href="resources/css/style.css" /-->
    
    <link rel="stylesheet" href="resources/css/fontawesome.min.css" />
    <script src="resources/js/jquery.min.js"></script>
    <script src="resources/js/popper.min.js"></script>
    <script src="resources/js/bootstrap.min.js"></script>
    <script src="resources/js/js.min.js"></script>
    <script src="resources/js/utils.js"></script>
    <script src="resources/js/sakura.js"></script>


</head>

<body>
    <div>
        <!-- NAVBAR -->
        <nav class="navbar navbar-expand-lg navbar-light">
            <a class="navbar-brand" href="#">
				<img src="images/japanicon-white-drop.png" width="115" height= "47" class="d-inline-block align-top" alt="">
			</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#" onclick = "updatePage('Tokyo');" >TOKYO</a>
                    </li>
					<li class="nav-item">
                        <a class="nav-link" href="#" onclick = "updatePage('Kyoto');" >KYOTO</a>
                    </li>
					<li class="nav-item">
                        <a class="nav-link" href="#" onclick = "updatePage('Sapporo');" >SAPPORO</a>
                    </li>
					<li class="nav-item">
                        <a class="nav-link" href="#" onclick = "updatePage('Yokohoma');" >YOKOHAMA</a>
                    </li>
					<li class="nav-item">
                        <a class="nav-link" href="#" onclick = "updatePage('Osaka');" >OSAKA</a>
                    </li>
					<li class="nav-item">
                        <a class="nav-link" href="#" onclick = "updatePage('Nagoya');" >NAGOYA</a>
                    </li>
                </ul>
            </div>
        </nav>
		<div>
			<img src = "images/header.jpg" class = "header_img">
			<div class = "separator"></div>
		</div>
		<br>
		<div id = "container">
		<!--CONTENT-->
			<div class="container">
				<div class="row">
					<div class="col-6">
						<iframe id = "ifrm" class="responsive-iframe" src="https://www.youtube.com/embed/WLIv7HnZ_fE?autoplay=1&mute=1" frameborder="0"></iframe>
					</div>
					<div class="col-6">
						<div class = "article-container">
							<h1 id = "header" class = "header" >JAPAN</h1>
							<p id = "article">
								An island country in East Asia and eleventh-most populous country in the world,
								as well as one of the most densely populated and urbanized. Japan is divided into 47 administrative prefectures 
								and eight traditional regions. The Greater Tokyo Area is the most populous metropolitan area in the world, with more than 37.4 million residents.
								<br>
								<br>
								The name for Japan in Japanese is written using the kanji <b>日本</b> and is pronounced Nippon or Nihon.
								Before <b>日本</b> was adopted in the early 8th century, the country was known in China as Wa 
								(<b>倭</b>, changed in Japan around 757 to <b>和</b>) and in Japan by the endonym Yamato.
							</p>
						</div>
					</div>
				</div>
			</div>
			<br>
			
			<!--Graph-->
			
			<div class="content">
				<div class = "row">
					<div class="col-6">
						<h1 class = "header">WEATHER</h1>
					</div>
					<div class="col-6">
						<h1 class = "header">TOP MUST VISIT PLACES</h1>
					</div>
				</div>

				<div class = "separator"></div>
				<div class = "row">
					<div class="col-6"><canvas height="500" id="chart-0"></canvas></div>
					<div class="col-6" id="divInfo" style="margin-top:25px;height:450px;overflow-y:auto;"></div>
				</div>
				<div class = "separator"></div>
			</div>
			
			
			
			<br>
			<!--Weather-->
			<div id="mainContent" >
				<div class="row">
					<!--div class="col-6" id="divWeather"></div-->
				</div>
			</div>
		</div>
    </div>

    <script>
		let weatherTemp = [];
		let weatherTime = [];
		let ch;
        $(document).ready(function() { 
			sakura();
			fetchWeather("Tokyo");
			fetchInfo("Tokyo");
			
        });

        var oInfo, oWeather;

        function fetchInfo(city) {
            $.ajax({
                url: "php/ajax.php",
                type: "POST",
                data: {
                    fn: "fetchInfo",
                    city: city
                },
                success: function(response) {
                    oInfo = JSON.parse(response);
                    $("#divInfo").empty();
                    $(oInfo.results).each(function(i, x) {
                        $("#divInfo").append((i + 1) + ". " + x.name + ", " + x.location.address + "<br>");
                    });
                }
            });
        }

        function fetchWeather(city) {
            $.ajax({
                url: "php/ajax.php",
                type: "POST",
                data: {
                    fn: "fetchWeather",
                    city: city
                },
                success: function(response) {
                    oWeather = JSON.parse(response);
                    $("#divWeather").empty();
					//
                    $(oWeather.list).each(function(i, x) {
                        //$("#divWeather").append(((x.main.temp)) + "<br>");
						weatherTemp[i] = x.main.temp;
						//console.log(weatherTemp);
                    });
					
					$(oWeather.list).each(function(i, x) {
						
						let unix_timestamp = x.dt;
						var date = new Date(unix_timestamp * 1000);
						var day = date.getDate();
						var month = (date.getMonth())+1;
						
						var hours = date.getHours();
						var minutes = date.getMinutes();
						var ampm = hours >= 12 ? 'pm' : 'am';
						hours = hours % 12;
						hours = hours ? hours : 12; // the hour '0' should be '12'
						minutes = minutes < 10 ? '0'+minutes : minutes;
						var strTime = month+'/' +day+ ' ' +hours + ':' + minutes + ' ' + ampm;
						
                        //$("#divWeather").append(((formattedTime)) + "<br>");
						weatherTime[i] = strTime;
                    });
					
					loadChart();
                }
            });
        }

        function updatePage(city) {
			let url;
			let article;
			if 		(city == "Tokyo")	{
				url = "https://www.youtube.com/embed/cS-hFKC_RKI?autoplay=1&mute=1";
				article = "Tokyo is Japan's capital and the world's most populous metropolis. It is also one of Japan's 47 prefectures, consisting of 23 central city wards and multiple cities, towns and villages west of the city center. The Izu and Ogasawara Islands are also part of Tokyo. Today, Tokyo offers a seemingly unlimited choice of shopping, entertainment, culture and dining to its visitors. The city's history can be appreciated in districts such as Asakusa and in many excellent museums, historic temples and gardens. Contrary to common perception, Tokyo also offers a number of attractive green spaces in the city center and within relatively short train rides at its outskirts.";
			}
			else if (city == "Kyoto")	{
				url = "https://www.youtube.com/embed/Jd1wzlwtKJ0?autoplay=1&mute=1";
				article = "Kyoto (京都, Kyōto) served as Japan's capital and the emperor's residence from 794 until 1868. It is one of the country's ten largest cities with a population of 1.5 million people and a modern face. Over the centuries, Kyoto was destroyed by many wars and fires, but due to its exceptional historic value, the city was dropped from the list of target cities for the atomic bomb and escaped destruction during World War II. Countless temples, shrines and other historically priceless structures survive in the city today.";
			}
			else if (city == "Sapporo")	{
				url = "https://www.youtube.com/embed/tPaxLffVcfA?autoplay=1&mute=1";
				article = "Sapporo (札幌, 'important river flowing through a plain' in Ainu language) is the capital of Hokkaido and Japan's fifth largest city. Sapporo is also one of the nation's youngest major cities. In 1857, the city's population stood at just seven people. In the beginning of the Meiji Period, when the development of Hokkaido was started on a large scale, Sapporo was chosen as the island's administrative center and enlarged according to the advice of foreign specialists. Consequently, Sapporo was built based on a rectangular street system. Sapporo became world famous in 1972 when the Olympic Winter Games were held there. Today, the city is well known for its ramen, beer, and the annual snow festival held in February.";
			}
			else if (city == "Yokohama"){
				url = "https://www.youtube.com/embed/p_pTYc3PYBQ?autoplay=1&mute=1";
				article = "Yokohama (横浜) is Japan's second largest city with a population of over three million. Yokohama is located less than half an hour south of Tokyo by train, and is the capital of Kanagawa Prefecture. Towards the end of the Edo Period (1603-1867), during which Japan maintained a policy of self-isolation, Yokohama's port was one of the first to be opened to foreign trade in 1859. Consequently, Yokohama quickly grew from a small fishing village into one of Japan's major cities. Until today, Yokohama remains popular among expats, has one of the world's largest Chinatowns and preserves some former Western residences in the Yamate district.";
			}
			else if (city == "Osaka")	{
				url = "https://www.youtube.com/embed/NXdTwcoGE_w?autoplay=1&mute=1";
				article = "Osaka (大阪, Ōsaka) is Japan's second largest metropolitan area after Tokyo. It has been the economic powerhouse of the Kansai Region for many centuries. Osaka was formerly known as Naniwa. Before the Nara Period, when the capital used to be moved with the reign of each new emperor, Naniwa was once Japan's capital city, the first one ever known. In the 16th century, Toyotomi Hideyoshi chose Osaka as the location for his castle, and the city may have become Japan's political capital if Tokugawa Ieyasu had not terminated the Toyotomi lineage after Hideyoshi's death and moved his government to distant Edo (Tokyo).";
			}
			else						{
				url = "https://www.youtube.com/embed/UuskpUCyD2o?autoplay=1&mute=1";
				article = "With over two million inhabitants, Nagoya (名古屋) is Japan's fourth most populated city. It is the capital of Aichi Prefecture and the principal city of the Nobi plain, one of Honshu's three large plains and metropolitan and industrial centers. Nagoya developed as the castle town of the Owari, one of the three branches of the ruling Tokugawa family during the Edo Period. Much of the city, including most of its historic buildings, were destroyed in the air raids of 1945. The Toyota Motor Corporation maintains its headquarters just outside of Nagoya.";
			}
			$('#ifrm').attr('src', url);
			$("#header").text(city);
			$("#article").text(article);
			
			fetchWeather(city + ",JP");
            fetchInfo(city + ",JP");
        }
		
		function loadChart(){
			var presets = window.chartColors;
			var utils = Samples.utils;
			var inputs = {
				min: -100,
				max: 100,
				count: 8,
				decimals: 2,
				continuity: 1
			};

			var options = {
				maintainAspectRatio: false,
				spanGaps: false,
				elements: {
					line: {
						tension: 0.000001
					}
				},
				plugins: {
					filler: {
						propagate: false
					}
				},
				scales: {
					xAxes: [{
						// ticks: {
							// autoSkip: false,
							// maxRotation: 0
						// },
						gridLines: {
							display:false
						}
					}],
					yAxes: [{
						gridLines: {
							display:false
							}
							}]
				}
			};

			['start'].forEach(function(boundary, index) {

				// reset the random seed to generate the same data for all charts
				utils.srand(8);

				new Chart('chart-' + index, {
					type: 'line',
					data: {
						labels: weatherTime.slice(0,10),
						datasets: [{
							backgroundColor: utils.transparentize(presets.yellow),
							borderColor: presets.yellow,
							data: weatherTemp.slice(0,10),
							label: 'Temperature (Fahrenheit)',
							fill: boundary
						}]
					},
					options: Chart.helpers.merge(options, {
						plugins: {
							title: {
								text: 'fill: ' + boundary,
								display: true
							}
						},
					})
				});
			});
			toggleSmooth();
		}

		// eslint-disable-next-line no-unused-vars
		function toggleSmooth() {
			Chart.helpers.each(Chart.instances, function(chart) {
				chart.options.elements.line.tension = 0.4 ;
				chart.update();
			});
		}
		
		function sakura(){
			var sakura = new Sakura('body', {
			colors: [
			  {
				gradientColorStart: 'rgba(255, 183, 197, 0.9)',
				gradientColorEnd: 'rgba(255, 197, 208, 0.9)',
				gradientColorDegree: 120,
			  },
			  {
				gradientColorStart: 'rgba(255,189,189)',
				gradientColorEnd: 'rgba(227,170,181)',
				gradientColorDegree: 120,
			  },
			  {
				gradientColorStart: 'rgba(212,152,163)',
				gradientColorEnd: 'rgba(242,185,196)',
				gradientColorDegree: 120,
			  },
			],
			delay: 150,
		  });
		}
    </script>
</body>

</html>