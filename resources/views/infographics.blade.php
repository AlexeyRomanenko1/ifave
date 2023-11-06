<!DOCTYPE html>
<html>
<head>
    <title>Infographics</title>
    <style>
        #chart-container {
            position: relative;
        }

        #chart-title {
            position: absolute;
            top: 10px;
            left: 10px;
            font-size: 16px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div id="chart-container">
        <canvas id="myChart"></canvas>
        <h2 id="chart-title">Professional Infographic</h2>
    </div>
    <button id="downloadButton">Download Image</button>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
    <script>
        const ctx = document.getElementById('myChart').getContext('2d');
        const chart = new Chart(ctx, {
            type: 'bar', // Use 'bar' type for horizontal bars
            data: {
                labels: ['Data 1', 'Data 2', 'Data 3', 'Data 4', 'Data 5'],
                datasets: [{
                    label: 'Histogram Data',
                    data: [10, 20, 30, 40, 50],
                    backgroundColor: 'white', // Set the background color to white
                    borderColor: 'blue', // Set the border color
                    borderWidth: 1, // Add a border
                }],
            },
            options: {
                indexAxis: 'y', // Display bars along the y-axis for horizontal bars
                scales: {
                    x: { // Use 'x' scale for horizontal bars
                        beginAtZero: true,
                    },
                },
                plugins: {
                    legend: {
                        display: false, // Hide the legend
                    },
                },
            },
        });

        // Customize the appearance of the chart
        chart.options.plugins.title = {
            display: true,
            text: '',
            font: {
                size: 16,
                weight: 'bold',
            },
        };

        chart.options.plugins.tooltips = {
            backgroundColor: 'white',
            titleColor: 'black',
            bodyColor: 'black',
        };

        // Add event listener to download the chart as an image in JPG format
        const downloadButton = document.getElementById('downloadButton');
        downloadButton.addEventListener('click', () => {
            const chartTitle = document.getElementById('chart-title').innerText;
            chart.options.plugins.title.text = chartTitle; // Set the chart title

            const chartContainer = document.getElementById('chart-container');
            const canvas = document.getElementById('myChart');
            html2canvas(chartContainer).then(function (canvas) {
                chart.options.plugins.title.text = ''; // Reset the chart title
                const dataURL = canvas.toDataURL('image/jpeg', 1.0); // Set image format (JPEG) and quality
                const link = document.createElement('a');
                link.href = dataURL;
                link.download = 'infographics.jpg'; // Use the JPG file extension
                link.click();
            });
        });
    </script>
</body>
</html>
