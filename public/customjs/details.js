// Product details gallery.
//
// - Clicking a thumbnail switches the main image (with a fade).
// - Clicking the main image opens it enlarged in a Bootstrap modal
//   (lightbox); the modal also closes with Esc or a backdrop click.
//   Previously there was no way to enlarge the image at all, and the
//   thumbnails did nothing on the port-forwarded site because this
//   script was loaded from a localhost URL that never resolved there.
document.addEventListener("DOMContentLoaded", function () {
	const mainThumbnail = document.getElementById("mainThumbnail");
	const lightboxImage = document.getElementById("lightboxImage");
	const lightbox = document.getElementById("imageLightbox");
	const buttons = document.querySelectorAll(".thumbnail-button");

	if (!mainThumbnail) {
		return;
	}

	function showImage(src) {
		mainThumbnail.classList.add("opacity-0");

		setTimeout(function () {
			mainThumbnail.src = src;
			mainThumbnail.classList.remove("opacity-0");
			if (lightboxImage) {
				lightboxImage.src = src;
			}
		}, 500);
	}

	buttons.forEach((button) => {
		button.addEventListener("click", function () {
			const img = this.querySelector("img");
			if (!img) {
				return;
			}

			showImage(img.src);

			buttons.forEach((btn) => btn.classList.remove("thumbnail-active-ring"));
			this.classList.add("thumbnail-active-ring");
		});
	});

	// Keyboard users: Enter / Space on the main image opens the lightbox.
	mainThumbnail.addEventListener("keydown", function (e) {
		if (e.key === "Enter" || e.key === " ") {
			e.preventDefault();
			mainThumbnail.click();
		}
	});

	// Keep the lightbox in sync with the image currently shown, even if
	// the main image was changed after the modal markup was rendered.
	if (lightbox) {
		lightbox.addEventListener("show.bs.modal", function () {
			if (lightboxImage) {
				lightboxImage.src = mainThumbnail.src;
			}
		});
	}
});
