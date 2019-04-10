import $ from 'jquery';


class Search {
	// 1. describe and create/initiate our object
	constructor() {
		this.addSearchHTML();
		this.resultsDiv = $("#search-overlay__results");
		this.openButton = $(".js-search-trigger");
		this.closeButton = $(".search-overlay__close");
		this.searchOverlay = $(".search-overlay");
		this.searchField = $("#search-term");
		this.events();
		this.isOverlayOpen = false;
		this.isSpinnerVisble = false;
		this.previousValue;
		this.typingTimer;
	}

	// 2. events (what happens)
	events() {
		this.openButton.on("click", this.openOverlay.bind(this));
		this.closeButton.on("click", this.closeOverlay.bind(this));
		$(document).on("keydown", this.keyPressDispatcher.bind(this));		
		this.searchField.on("keyup", this.typingLogic.bind(this));
	}


	// 3. methods (function, actions...) verbs = run, walk, speak.
	typingLogic() {
		if (this.searchField.val() != this.previousValue) {
			clearTimeout(this.typingTimer);

			if (this.searchField.val()) {

			if (!this.isSpinnerVisble) {
				this.resultsDiv.html('<div class="spinner-loader"></div>');
				this.isSpinnerVisble = true;
		}
		this.typingTimer = setTimeout(this.getResults.bind(this), 750);

			} else {
				this.resultsDiv.html('');
				this.isSpinnerVisble = false;
			}
	
		}
		
		this.previousValue = this.searchField.val();
	}

	getResults() {
		$.getJSON(spData.root_url + '/wp-json/sp/v1/search?term=' + this.searchField.val(), (results) => {
			this.resultsDiv.html(`
				<div class="row">
					<div class="one-third">
						<h2 class="search-overlay__section-title">Information</h2>
						${results.information.length ? '<ul class="link-list min-list">' : '<p>Sökresultatet gav inga träffar.</p>'}
						${results.information.map(item => `<li><a href="${item.permalink}">${item.title}</a> ${item.postType == 'post'  ? `skrivet av ${item.authorName}` : ''}</li>`).join('')}
						${results.information.length ? '</ul>' : ''}
					</div>
					<div class="one-third">
						<h2 class="search-overlay__section-title">Kurser</h2>
						${results.kurser.length ? '<ul class="link-list min-list">' : `<p>Ingen kurs hittades på sökresultatet. <a href="${spData.root_url}/kurs">Visa alla kurser.</a></p>`}
						${results.kurser.map(item => `<li><a href="${item.permalink}">${item.title}</a></li>`).join('')}
						${results.kurser.length ? '</ul>' : ''}

						<h2 class="search-overlay__section-title">Skribent</h2>
						${results.skribent.length ? '<ul class="professor-cards">' : `<p>Ingen skribent hittades på sökresultatet.</p>`}
						${results.skribent.map(item => `
							<li class="professor-card__list-item">
              					<a class="professor-card" href="${item.permalink}">
               					 <img class="professor-card__image" src="${item.image}">
              					  <span class="professor-card__name">${item.title}</span>
              					</a>
           					 </li>
							`).join('')}
						${results.skribent.length ? '</ul>' : ''}

					</div>
					<div class="one-third">
						<h2 class="search-overlay__section-title">Restauranger</h2>
						${results.restauranger.length ? '<ul class="link-list min-list">' : `<p>Inget restauranger hittades på sökresultatet. <a href="${spData.root_url}/restauranger">Visa alla restauranger.</a></p>`}
						${results.restauranger.map(item => `<li><a href="${item.permalink}">${item.title}</a></li>`).join('')}
						${results.restauranger.length ? '</ul>' : ''}

						<h2 class="search-overlay__section-title">Evenemang</h2>
						${results.evenemang.length ? '' : `<p>Inga evenemang hittades på sökresultatet. <a href="${spData.root_url}/evenemang">Visa alla evenemang.</a></p>`}
						${results.evenemang.map(item => `
							<div class="event-summary">
          						<a class="event-summary__date t-center" href="${item.permalink}">
           						 <span class="event-summary__month">${item.month}</span>
           							 <span class="event-summary__day">${item.day}</span>  
         						 </a>
         						 <div class="event-summary__content">
         						   <h5 class="event-summary__title headline headline--tiny"><a href="${item.permalink}">${item.title}</a></h5>
          							  <p>${item.description}<a href="${item.permalink}" class="nu gray">Läs mer</a></p>
         						 </div>
      						 </div>
							`).join('')}
					</div>
				</div>
				`);
			this.isSpinnerVisble = false;
		});

	}

	keyPressDispatcher(e) {
		if(e.keyCode == 83 && !this.isOverlayOpen && !$("input, textarea").is(':focus')) {
			this.openOverlay();
		}

		if(e.keyCode == 27 && this.isOverlayOpen) {
			this.closeOverlay();
		}

	}


	openOverlay() {
		this.searchOverlay.addClass("search-overlay--active");
		$("body").addClass("body-no-scroll");
		this.searchField.val('');
		setTimeout(() => this.searchField.focus(), 301);
		console.log("our open method just ran");
		this.isOverlayOpen = true;
		return false;

	}

	closeOverlay() {
		this.searchOverlay.removeClass("search-overlay--active");
		$("body").removeClass("body-no-scroll");
		console.log("our close method just ran");
		this.isOverlayOpen = false;


	}

	addSearchHTML() {
		$("body").append(`
			<div class="search-overlay">
  <div class="search-overlay__top">
    <div class="container">
      <i class="fa fa-search search-overlay__icon" aria-hidden="true"></i>
      <input type="text" class="search-term" placeholder="Vad letar du efter?" id="search-term">
      <i class="fa fa-window-close search-overlay__close" aria-hidden="true"></i>

    </div>
  </div>

  <div class="container">
    <div id="search-overlay__results"></div>
    </div>

</div>
			`);
	}
}

export default Search;