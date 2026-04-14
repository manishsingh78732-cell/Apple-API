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
font-size:32px;
letter-spacing:1px;
}

form{
text-align:center;
margin-bottom:30px;
}

input[type=text]{
width:320px;
padding:12px 15px;
border:none;
border-radius:30px;
outline:none;
font-size:16px;
box-shadow:0 4px 10px rgba(0,0,0,0.2);
}

button{
padding:12px 25px;
border:none;
border-radius:30px;
background:#000;
color:white;
font-size:15px;
margin-left:10px;
cursor:pointer;
transition:0.3s;
}

button:hover{
background:#333;
transform:scale(1.05);
}

.artist{
background:rgba(255,255,255,0.25);
backdrop-filter:blur(10px);
padding:20px;
margin-bottom:20px;
border-radius:15px;
display:flex;
align-items:center;
gap:20px;
box-shadow:0 8px 25px rgba(0,0,0,0.2);
transition:0.3s;
}

.artist:hover{
transform:translateY(-5px);
box-shadow:0 15px 30px rgba(0,0,0,0.3);
}

.artist img{
width:90px;
height:90px;
border-radius:50%;
object-fit:cover;
box-shadow:0 5px 15px rgba(0,0,0,0.3);
}

.artist-info{
flex:1;
}

.artist-info b{
font-size:20px;
color:#fff;
}

.artist-info p{
margin:5px 0;
color:#f1f1f1;
}

.artist a{
display:inline-block;
margin-top:5px;
color:#fff;
font-weight:bold;
text-decoration:none;
}

.artist a:hover{
text-decoration:underline;
}

input[type=checkbox]{
width:20px;
height:20px;
cursor:pointer;
}

.result-url{
margin-top:30px;
background:white;
padding:20px;
border-radius:10px;
box-shadow:0 5px 20px rgba(0,0,0,0.2);
font-size:16px;
line-height:1.7;
}

h3{
color:white;
margin-top:40px;
}

@media(max-width:768px){

.artist{
flex-direction:column;
text-align:center;
}

.artist img{
margin-bottom:10px;
}

}

</style>

<script>

function  (){
let checkboxes=document.querySelectorAll("input[name='artist_check']:checked");

let urls=[];

checkboxes.forEach(function(cb){
urls.push(cb.value);
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

<hr>

<?php if(!empty($searchResults)): ?>

<?php foreach($searchResults as $artist): ?>

<div class="artist">

<input type="checkbox"
name="artist_check"
value="<?php echo $artist['artistViewUrl']; ?>"
onclick="getSelectedURL()">

<img src="<?php echo $artist['artworkUrl100']; ?>">

<div>

<b><?php echo $artist['artistName']; ?></b>

<p>Track: <?php echo $artist['trackName']; ?></p>

<a href="<?php echo $artist['artistViewUrl']; ?>" target="_blank">
Open Artist
</a>

</div>

</div>

<?php endforeach; ?>

<?php endif; ?>


<h3>Selected Artist URL</h3>

<div id="selected_url" class="result-url"></div>

</body>
</html>