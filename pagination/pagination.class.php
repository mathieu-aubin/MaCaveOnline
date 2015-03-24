<?php

class Pagination {
	private $page_name; // Nom de la page
	private $current_page; // Numéro page courante
	private $total_pages; // Nombre total de pages
	private $showitems; // Nombre d'items
	private $output; // Sortie html de la pagination
	private $options = array( // Options
		'range' => 2,
		'posts_per_page' => 10,
		'text_first_page' => '&laquo;',
		'text_last_page' => '&raquo;',
		'text_next_page' => '+',
		'text_previous_page' => '--'
	);

	function __construct($page_name, $current_page, $total_posts, $options = array()) {
		$this->options = $options + $this->options;
		$this->page_name = $page_name;
		$this->current_page = intval($current_page);
		$this->total_pages = ceil(intval($total_posts)/intval($this->options['posts_per_page']));
		$this->showitems = ($this->options['range']*2)+1;
		$this->generateOutput();
	}

	function generateOutput() {
		if (1 != $this->total_pages) {
			if ($this->options['text_first_page']) {
				// Si la page courante est supérieur à 2 et si la page courante est supérieur à range+1 et si la limite d'affichage est inférieur au nombre total de page
				if ($this->current_page > 2 && $this->current_page > $this->options['range'] + 1 && $this->showitems < $this->total_pages) {
					$this->output .= "<a href='".sprintf($this->page_name, 1)."' class='inactive first'>".$this->options['text_first_page']."</a>";
				}
			}

			if ($this->options['text_previous_page']) {
				if ($this->current_page > 1 && $this->showitems < $this->total_pages) {
					$this->output .= "<a href='".sprintf($this->page_name, $this->current_page - 1)."' class='inactive prev'>".$this->options['text_previous_page']."</a>";
				}
			}
			
			for ($i = 1 ; $i <= $this->total_pages ; $i++) {
				// si pageCourante-2 < i < pagecourante+2
				if (($i >= $this->current_page - $this->options['range'] && $i <= $this->current_page + $this->options['range']) || ($this->total_pages <= $this->showitems)) {
					$this->output .= ($this->current_page == $i) ? "<span class='current'>".$i."</span>" : "<a href='".sprintf($this->page_name, $i)."' class='inactive'>".$i."</a>";
				}
			}

			if ($this->options['text_next_page']) {
				if ($this->current_page < $this->total_pages - 1 && $this->showitems < $this->total_pages) {
					$this->output .= "<a href='".sprintf($this->page_name, $this->current_page + 1)."' class='inactive next'>".$this->options['text_next_page']."</a>";
				}
			}

			if ($this->options['text_last_page']) {
				if ($this->current_page < $this->total_pages-1 && $this->current_page + $this->options['range'] < $this->total_pages && $this->showitems < $this->total_pages) {
					$this->output .= "<a href='".sprintf($this->page_name, $this->total_pages)."' class='inactive last'>".$this->options['text_last_page']."</a>";
				}
			}
		}
	}

	function display() {
		echo($this->output);
	}
}

?>