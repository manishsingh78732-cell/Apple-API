<?php
$searchResults = [];

if (isset($_GET['artist']) && !empty($_GET['artist'])) {

    $artistName = trim($_GET['artist']);
    $encodedName = urlencode($artistName);

    $url = "https://itunes.apple.com/search?term={$encodedName}&entity=song&limit=20";

    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true
    ]);

    $response = curl_exec($curl);
    curl_close($curl);

    if ($response) {
        $data = json_decode($response, true);
        if (!empty($data['results'])) {
            $searchResults = $data['results'];
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Apple Artist Search</title>

<style>
body{
    font-family:'Segoe UI',sans-serif;
    background:linear-gradient(135deg,#ff5f6d,#ffc371);
    min-height:100vh;
    padding:40px;
    margin:0;
}

h2{
    text-align:center;
    color:white;
    margin-bottom:30px;
}

form{
    text-align:center;
    margin-bottom:30px;
}

input[type=text]{
    width:320px;
    padding:12px;
    border:none;
    border-radius:30px;
    outline:none;
}

button{
    padding:12px 25px;
    border:none;
    border-radius:30px;
    background:#000;
    color:white;
    cursor:pointer;
}

.artist{
    background:rgba(255,255,255,0.25);
    padding:15px;
    margin-bottom:15px;
    border-radius:10px;
    display:flex;
    align-items:center;
    gap:15px;
}

.artist img{
    width:80px;
    border-radius:50%;
}

.result-url{
    margin-top:20px;
    background:white;
    padding:15px;
    border-radius:10px;
}
</style>

<script>

// ✅ FUNCTION FIXED
function getSelectedURL(){
    let checkboxes = document.querySelectorAll("input[name='artist_check']:checked");
    let urls = [];

    checkboxes.forEach(function(cb){
        urls.push(`<a href="${cb.value}" target="_blank">${cb.value}</a>`);
    });

    document.getElementById("selected_url").innerHTML = urls.join("<br>");
}

</script>

</head>

<body>

<h2>🎵 Search Apple Music Artist</h2>

<form method="GET">
    <input type="text" name="artist" placeholder="Artist name..." required>
    <button type="submit">Search</button>
</form>

<?php if(!empty($searchResults)): ?>

    <?php foreach($searchResults as $artist): ?>

        <div class="artist">

            <!-- ✅ CHECKBOX -->
            <input type="checkbox"
                   name="artist_check"
                   value="<?php echo $artist['artistViewUrl']; ?>"
                   onclick="getSelectedURL()">

            <img src="<?php echo $artist['artworkUrl100']; ?>">

            <div>
                <b><?php echo $artist['artistName']; ?></b>
                <p>Track: <?php echo $artist['trackName']; ?></p>

                <!-- ✅ DIRECT OPEN -->
                <a href="<?php echo $artist['artistViewUrl']; ?>" target="_blank">
                    Open Artist
                </a>
            </div>

        </div>

    <?php endforeach; ?>

<?php endif; ?>

<h3 style="color:white;">Selected Artist URLs</h3>

<div id="selected_url" class="result-url"></div>

</body>
</html>