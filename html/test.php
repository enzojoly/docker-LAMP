<!DOCTYPE html>
<html>

<head>
    <title>Twin Cities Flickr Test</title>
    <style>
        #flickr-images-container img {
            width: 200px;
            height: 200px;
            object-fit: cover;
            margin: 5px;
        }
    </style>
</head>
<body>
    <h1>Flickr Images Display</h1>
    <div id="flickr-images-container"></div>

    <?php require_once 'config.php'; ?>

    <script>
        function fetchFlickrImages(city, latitude, longitude) {
            const flickrApiKey = '<?php echo FLICKR_API_KEY; ?>';
            const flickrApiUrl = `https://www.flickr.com/services/rest/?method=flickr.photos.search&api_key=${flickrApiKey}&format=json&nojsoncallback=1&sort=relevance&privacy_filter=1&per_page=5&lat=${latitude}&lon=${longitude}&text=${encodeURIComponent(city)}`;

            fetch(flickrApiUrl)
                .then(response => response.json())
                .then(data => {
                    const photos = data.photos.photo;
                    const imageContainer = document.getElementById('flickr-images-container');
                    imageContainer.innerHTML = ''; // Clear previous images
                    photos.forEach(photo => {
                        const imageUrl = `https://farm${photo.farm}.staticflickr.com/${photo.server}/${photo.id}_${photo.secret}_m.jpg`;
                        const imgElement = document.createElement('img');
                        imgElement.src = imageUrl;
                        imgElement.alt = photo.title;
                        imageContainer.appendChild(imgElement);
                    });
                })
                .catch(error => console.error('Error fetching Flickr images:', error));
        }

        // Example usage with Liverpool coordinates
        document.addEventListener('DOMContentLoaded', function() {
            fetchFlickrImages('Liverpool', 53.4084, -2.9916); // Use actual coordinates for Liverpool
        });
    </script>
</body>

</html>
