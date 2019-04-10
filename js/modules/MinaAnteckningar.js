import $ from 'jquery';

class MinaAnteckningar {
	constructor() {
		this.events();
	}

	events() {
		$("#mina-anteckningar").on("click", ".delete-note", this.deleteNote.bind(this));
		$("#mina-anteckningar").on("click", ".edit-note", this.editNote.bind(this));
		$("#mina-anteckningar").on("click", ".update-note", this.updateNote.bind(this));
		$(".submit-note").on("click", this.createNote.bind(this));

	}

	//Methods will go here
	editNote(e) {
		var thisNote = $(e.target).parents("li");
		if (thisNote.data("state") == "editable") {
			this.makeNoteReadOnly(thisNote);
		} else {
			this.makeNoteEditable(thisNote);

		}
		
	}

	makeNoteEditable(thisNote) {
		thisNote.find(".edit-note").html('<i class="fa fa-times" aria-hidden="true"></i> Avbryt');
		thisNote.find(".note-title-field, .note-body-field").removeAttr("readonly").addClass("note-active-field");
		thisNote.find(".update-note").addClass("update-note--visible");
		thisNote.data("state", "editable");
	}

	makeNoteReadOnly(thisNote) {
		thisNote.find(".edit-note").html('<i class="fa fa-pencil" aria-hidden="true"></i> Redigera');
		thisNote.find(".note-title-field, .note-body-field").attr("readonly", "readonly").removeClass("note-active-field");
		thisNote.find(".update-note").removeClass("update-note--visible");
		thisNote.data("state", "cancel");
	}


	deleteNote(e) {
		var thisNote = $(e.target).parents("li");

		$.ajax({
			beforeSend: (xhr) => {
				xhr.setRequestHeader('X-WP-Nonce', spData.nonce);
			},
			url: spData.root_url + '/wp-json/wp/v2/anteckning/' + thisNote.data('id'),
			type: 'DELETE',

			success: (response) => {
				thisNote.slideUp();
				console.log("Grattis");
				console.log(response);
				if (response.userNoteCount < 100) {
					$(".note-limit-message").removeClass("active");
				}
			},
			error: (response) => {
				console.log("Sorry");
				console.log(response);
			}
		});	
	}

	updateNote(e) {
		var thisNote = $(e.target).parents("li");

		var ourUpdatedPost = {
			'title': thisNote.find(".note-title-field").val(),
			'content': thisNote.find(".note-body-field").val()
		}

		$.ajax({
			beforeSend: (xhr) => {
				xhr.setRequestHeader('X-WP-Nonce', spData.nonce);
			},
			url: spData.root_url + '/wp-json/wp/v2/anteckning/' + thisNote.data('id'),
			type: 'POST',
			data: ourUpdatedPost,
			success: (response) => {
				this.makeNoteReadOnly(thisNote);
				console.log("Grattis");
				console.log(response);
			},
			error: (response) => {
				console.log("Sorry");
				console.log(response);
			}
		});	
	}

	createNote(e) {
			var ourNewPost = {
			'title': $(".new-note-title").val(),
			'content': $(".new-note-body").val(),
			'status': 'publish'
		}

		$.ajax({
			beforeSend: (xhr) => {
				xhr.setRequestHeader('X-WP-Nonce', spData.nonce);
			},
			url: spData.root_url + '/wp-json/wp/v2/anteckning/',
			type: 'POST',
			data: ourNewPost,
			success: (response) => {
				$(".new-note-title, .new-note-body").val('');
				$(`
				<li data-id="${response.id}">
		          <input readonly class="note-title-field" value="${response.title.raw}">
		          <span class="edit-note"><i class="fa fa-pencil" aria-hidden="true"></i> Redigera</span>
		          <span class="delete-note"><i class="fa fa-trash-o" aria-hidden="true"></i> Ta bort</span>
		         <textarea readonly class="note-body-field">${response.content.raw}</textarea>
		          <span class="update-note btn btn--blue btn--small"><i class="fa fa-arrow-right" aria-hidden="true"></i> Spara</span>

        </li>
					`).prependTo("#mina-anteckningar").hide().slideDown();
				console.log("Grattis");
				console.log(response);
			},
			error: (response) => {
				if(response.responseText == "Du har nått max antal tillåtna anteckningar") {
					$(".note-limit-message").addClass("active");
				}
				console.log("Sorry");
				console.log(response);
			}
		});	
	}
}

export default MinaAnteckningar;