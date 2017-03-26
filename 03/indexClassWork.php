<html>
<body>
<head>
<style>
.red{
	color: red;
	font-size: 25px;
}</style></head>
<table>
<?php
$index = 0; // Проверка записана ли буква или нет
$vowels ="AEIOUYaeiouy"; // Строка с гласными буквами
$parity = 0; // четность строки
$offers = ""; // Полученные предложения
$output = ""; // Окончательный результат
// Изначальный текст
$text="Anyone who reads Old and Middle English literary texts will be familiar with the mid-brown volumes of the EETS, with the symbol of Alfred's jewel embossed on the front cover. Most of the works attributed to King Alfred or to Aelfric, along with some of those by bishop Wulfstan and much anonymous prose and verse from the pre-Conquest period, are to be found within the Society's three series; all of the surviving medieval drama, most of the Middle English romances, much religious and secular prose and verse including the English works of John Gower, Thomas Hoccleve and most of Caxton's prints all find their place in the publications. Without EETS editions, study of medieval English texts would hardly be possible.

As its name states, EETS was begun as a 'club', and it retains certain features of that even now. It has no physical location, or even office, no paid staff or editors, but books in the Original Series are published in the first place to satisfy subscriptions paid by individuals or institutions. This means that there is need for a regular sequence of new editions, normally one or two per year; achieving that sequence can pose problems for the Editorial Secretary, who may have too few or too many texts ready for publication at any one time. Details on a separate sheet explain how individual (but not institutional) members can choose to take certain back volumes in place of the newly published volumes against their subscriptions. On the same sheet are given details about the very advantageous discount available to individual members on all back numbers. In 1970 a Supplementary Series was begun, a series which only appears occasionally (it currently has 24 volumes within it); some of these are new editions of texts earlier appearing in the main series. Again these volumes are available at publication and later at a substantial discount to members. All these advantages can only be obtained through the Membership Secretary (the books are sent by post); they are not available through bookshops, and such bookstores as carry EETS books have only a very limited selection of the many published.

Editors, who receive no royalties or expenses and who are only very rarely commissioned by the Society, are encouraged to approach the Editorial Secretary with a detailed proposal of the text they wish to suggest to the Society early in their work; interest may be expressed at that point, but before any text is accepted for publication the final typescript must be approved by the Council (a body of some twenty scholars), and then assigned a place in the printing schedule. The Society now has a stylesheet to guide editors in the layout and conventions acceptable within its series. No prescriptive set of editorial principles is laid down, but it is usually expected that the evidence of all relevant medieval copies of the text(s) in question will have been considered, and that the texts edited will be complete whatever their length. Editions are directed at a scholarly readership rather than a popular one; though they normally provide a glossary and notes, no translation is provided.

EETS was founded in 1864 by Frederick James Furnivall, with the help of Richard Morris, Walter Skeat, and others, to bring the mass of unprinted Early English literature within the reach of students. It was also intended to provide accurate texts from which the New (later Oxford) English Dictionary could quote; the ongoing work on the revision of that Dictionary is still heavily dependent on the Society's editions, as are the Middle English Dictionary and the Toronto Dictionary of Old English. In 1867 an Extra Series was started, intended to contain texts already printed but not in satisfactory or readily obtainable editions; this series was discontinued in 1921, and from then on all the Society's editions, apart from the handful in the Supplementary Series described above, were listed and numbered as part of the Original Series. In all the Society has now published some 475 volumes; all except for a very small number (mostly of editions superseded within the series) are available in print. The early history of the Society is only traceable in outline: no details about nineteenth-century membership are available, and the secretarial records of the early twentieth century were largely lost during the second world war. By the 1950s a very large number of the Society's editions were out of print, and finances allowed for only a very limited reprinting programme. Around 1970 an advantageous arrangement was agreed with an American reprint firm to make almost all the volumes available once more whilst maintaining the membership discounts. Though this arrangement was superseded towards the end of the twentieth century and the cost of reprinting has reverted to the Society, as a result of the effort then it has proved possible to keep the bulk of the list in print."; 
/*Сохраняем в $offers нечетные предложения*/
for ($i=0; $i<strlen($text); $i++){ 
		/* Проверка на окончание предложения */
    if ($text[$i]=="." || $text[$i]=="!" || $text[$i]=="?") { 
	$parity = $parity + 1;
	}
	/*Проверка на четность предложения*/
	if($parity%2 == 0)
	{
		$offers .= $text[$i]; 
	}
}		
		/* Подсвечивание гласных*/
	for($i=0; $i<strlen($offers); $i++)
{	/* Проверка гласная буква или нет*/
			for($j=0; $j < strlen($vowels); $j++)
			{
				if($offers[$i] == $vowels[$j])
				{
						$output .= "<font class = 'red'>$offers[$i]</font>";
						$index = 1;
				} 
			}
			/*Если буква не гласная записываем*/
	if($index == 0)
	{
		$output .= $offers[$i];
	}
	/*Если уже записана обнуляем индекс*/
	else
	{
		$index = 0;
	}
}	
	/* Печать результатов*/
	print $output;
?>
</table>
</body>
</html>


