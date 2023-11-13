<div>
    {!! $chart->container() !!}
</div>
{!! $chart->script() !!}

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.min.js" charset="utf-8"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<script>
    const canvas = document.getElementById("{{ $chart->id }}")
    canvas.style.background = '#ffff';
    // $(document).on('ready', function() {
    setTimeout(function() {
        // Get the canvas element containing the chart
        var chartCanvas = document.getElementById('{{ $chart->id }}');
        var context = chartCanvas.getContext('2d');

        // Create a new canvas with padding
        var padding = 50;
        var newCanvas = document.createElement('canvas');
        newCanvas.width = chartCanvas.width + 2 * padding;
        newCanvas.height = chartCanvas.height + 2 * padding + 50;
        var newContext = newCanvas.getContext('2d');

        // Set the background color to white
        newContext.fillStyle = '#ffffff';
        newContext.fillRect(0, 0, newCanvas.width, newCanvas.height);

        // Draw the existing chart onto the new canvas with padding
        newContext.drawImage(chartCanvas, padding, padding + 50);

        // Add text to the new canvas
        newContext.fillStyle = '#000000'; // Set text color to black

        // Heading (h4)
        newContext.font = 'bold 44px Arial'; // Set font size and style
        newContext.fillText('Top Singers in The World', padding, padding + 20);

        // Paragraph (p)
        newContext.font = '20px Arial'; // Reset font size and style
        newContext.fillText('as voted by iFave visitors', padding + 15, padding + 60);

        // Line break
        newContext.fillText('', 10, 60);

        // Bottom text (single line with line break)
        newContext.fillText('Visit ifave.com for more awesome rankings and infographics that you will enjoy!', 640, newCanvas.height - 10);

        // Convert the new canvas to base64 image data
        var imageData = newCanvas.toDataURL('image/png');

        // Create a hidden form and submit the base64 data to the server
        var form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("save-chart-image") }}';
        form.style.display = 'none'; // Hide the form

        // Add CSRF token field
        var csrfTokenInput = document.createElement('input');
        csrfTokenInput.type = 'hidden';
        csrfTokenInput.name = '_token';
        csrfTokenInput.value = '{{ csrf_token() }}';
        form.appendChild(csrfTokenInput);

        // Add image data field
        var input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'imageData';
        input.value = imageData;

        var question_name = document.createElement('input');
        question_name.type = 'hidden';
        question_name.name = 'question_name';
        question_name.value = '{{$question_name}}';

        var topic_name = document.createElement('input');
        topic_name.type = 'hidden';
        topic_name.name = 'topic_name';
        topic_name.value = '{{$topic_name}}';

        form.appendChild(input);
        form.appendChild(question_name);
        form.appendChild(topic_name);
        document.body.appendChild(form);

        // Submit the form asynchronously
        fetch(form.action, {
                method: form.method,
                body: new FormData(form),
            })
            .then(response => {
                return response.blob();
            })
            .then(blob => {
                // Create a temporary link element to trigger the download
                // Create a link element dynamically
                var link = document.createElement('a');

                // Set the href attribute to the URL of the Laravel route
                link.href = '/images/infographics/ifave-{{str_replace(" ","-",$topic_name)}}-{{str_replace(" ","-",$question_name)}}-infographics.png';

                // Set the download attribute with the desired filename
                link.download = 'ifave-{{str_replace(" ","-",$topic_name)}}-{{str_replace(" ","-",$question_name)}}-infographics.png';

                // Append the link element to the body
                document.body.appendChild(link);

                // Trigger a click on the link to start the download
                link.click();

                // Remove the link from the body (clean-up)
                document.body.removeChild(link);

                // Redirect after the download
                window.location.href = '/category/{{str_replace(" ","-",$topic_name)}}/{{str_replace(" ","-",$question_name)}}';

            })
            .catch(error => {
                console.error('Error downloading image:', error);
            })
            .finally(() => {
                // Clean up the form
                document.body.removeChild(form);
            });

    }, 1000);
    // })
</script>