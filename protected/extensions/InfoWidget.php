<?php

class InfoWidget extends CWidget {

	private $content;

    public function init() {
		$this->content = "";
		$this->content .= '<section class="post " id="post-5811">';
		$this->content .= '<header class="header">';
		$this->content .= '<h2 class="post-title">Erl&auml;uterungen</h2>';
		$this->content .= '</header>';
		$this->content .= '<aside class="meta">';
		$this->content .= '<article class="article">';
		//$this->content .= 'Diese Webseite dient der allgemeinen Information zu den Haushalten der vergangenen Jahre für';
		//$this->content .= 'das Land Nordrhein-Westfalen. Die angezeigten Daten entsprechen den ';
		//$this->content .= 'veröffentlichten Haushaltsentwürfen der Landesregierung.';
		$this->content .= 'Die Daten sind frei verfügbar. Die Veröffentlichung erfolgt ohne Gewähr. Quelle: Finanzministerium NRW';
		$this->content .= '<br/>';
		$this->content .= '<br/>';
		$this->content .= 'Durch Neuordnung einzelner Ressorts ist teilweise der Vorjahresvergleich nicht möglich. Trotz sorgfältiger Prüfung kann für Übertragungsfehler keine Haftung übernommen werden.';
		$this->content .= '<br/>';
		$this->content .= '<br/>';
		$this->content .= 'Anregungen nehmen wir gerne auf und sind hier zu erreichen:';
		$this->content .= '<br/>';
		$this->content .= '<a href="emailto:piratenfraktion@landtag.nrw.de">piratenfraktion@landtag.nrw.de</a>';
		$this->content .= '<br/>';
		$this->content .= '<br/>';
		$this->content .= '<p>Fragen &amp; Kommentare sind ebenfalls direkt über diese Website möglich.</p>';
		$this->content .= '</section>';
    }
 
    public function run() {
		echo $this->content;
    }

}

?>
