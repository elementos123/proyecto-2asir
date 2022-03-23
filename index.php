<?php

$text = "On the other hand, we denounce with righteous indignation and dislike men who are so beguiled and demoralized by the charms of pleasure\n of the moment, so blinded by desire, that they cannot foresee the pain and trouble that are bound to ensue; and equal blame belongs to those who fail in their duty through\n weakness of will, which is the same as saying through shrinking from toil and pain. These cases are perfectly simple and easy to distinguish. In a free hour, when our power of choice is untrammelled and when nothing prevents our being able to\n do what we like best, every pleasure is to be welcomed and every pain avoided. But in certain circumstances and owing to the claims of duty or the obligations of business it will frequently\n occur that pleasures have to be repudiated and annoyances accepted. The wise man therefore always holds in these matters to this principle of selection: he rejects pleasures to\n secure other greater pleasures, or else he endures\n pains to avoid worse pains.";
$textotemporal = "";
$lineaslimitesparasumarcontadorarray = 10;
$array = array();
$contadorstring = 0;
$contadorarray = 0;
$contadorparalineas = 0;
echo strlen($text);
while($contadorstring < strlen($text))
{
    $textotemporal = $textotemporal . $text[$contadorstring];

    if ($contadorparalineas == $lineaslimitesparasumarcontadorarray)
    {
        $array[$contadorarray] = $textotemporal;
        $contadorarray++;
        $contadorparalineas = 0;
        $textotemporal = "";
    }

    $contadorstring++;
    $contadorparalineas++;
}


for ($i=0; $i < count($array); $i++) 
{ 
    echo "I: " . $i . " texto: " . $array[$i] . "<br><br>";
}

?>