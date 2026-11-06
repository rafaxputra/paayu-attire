// Product details gallery.
//
// - Clicking a thumbnail switches the main image (with a fade) and moves
//   the orange ring to the clicked thumbnail.
// - Clicking the main image opens it enlarged in a Bootstrap modal
//   (lightbox) with prev/next navigation, a photo counter, a thumbnail
//   strip and zoom controls.
// - Runs immediately instead of waiting for DOMContentLoaded: this file
//   is loaded after the gallery markup, so the elements already exist,
//   and waiting for DOMContentLoaded proved fragile — on the forwarded
//   site the thumbnail clicks stopped working entirely (the listeners
//   were never attached).
(function () {
	function init() {
		var mainThumbnail = document.getElementById("mainThumbnail");
		var lightboxImage = document.getElementById("lightboxImage");
		var lightbox = document.getElementById("imageLightbox");
		var lightboxStage = document.getElementById("lightboxStage");
		var counter = document.getElementById("lightboxCounter");
		var buttons = Array.prototype.slice.call(document.querySelectorAll(".thumbnail-button"));
		var thumbs = Array.prototype.slice.call(document.querySelectorAll(".lightbox-thumb"));

		if (!mainThumbnail || buttons.length === 0) {
			return;
		}

		var images = buttons.map(function (button) {
			var img = button.querySelector("img");
			return img ? img.src : "";
		}).filter(Boolean);

		if (images.length === 0) {
			return;
		}

		var currentIndex = 0;
		var zoom = 1;

		// Stop the shimmer placeholder once the main image has loaded.
		if (mainThumbnail.complete && mainThumbnail.naturalWidth > 0) {
			mainThumbnail.classList.add("img-loaded");
		} else {
			mainThumbnail.addEventListener("load", function () {
				mainThumbnail.classList.add("img-loaded");
			});
		}

		function setZoom(value) {
			zoom = Math.min(2.5, Math.max(1, value));
			if (lightboxImage) {
				lightboxImage.style.transform = zoom === 1 ? "" : "scale(" + zoom + ")";
			}
			if (lightboxStage) {
				lightboxStage.style.overflow = zoom > 1 ? "auto" : "";
			}
		}

		function setActive(index) {
			currentIndex = ((index % images.length) + images.length) % images.length;
			buttons.forEach(function (button, i) {
				button.classList.toggle("thumbnail-active-ring", i === currentIndex);
			});
			thumbs.forEach(function (thumb, i) {
				thumb.classList.toggle("active", i === currentIndex);
			});
			if (counter) {
				counter.textContent = String(currentIndex + 1);
			}
		}

		function showImage(index) {
			setActive(index);
			setZoom(1);
			var src = images[currentIndex];

			mainThumbnail.classList.add("opacity-0");
			setTimeout(function () {
				mainThumbnail.src = src;
				mainThumbnail.classList.remove("opacity-0");
				if (lightboxImage) {
					lightboxImage.src = src;
				}
			}, 500);
		}

		buttons.forEach(function (button, i) {
			button.addEventListener("click", function () {
				showImage(i);
			});
		});

		thumbs.forEach(function (thumb, i) {
			thumb.addEventListener("click", function () {
				showImage(i);
			});
		});

		var prevBtn = document.getElementById("lightboxPrev");
		var nextBtn = document.getElementById("lightboxNext");
		if (prevBtn) {
			prevBtn.addEventListener("click", function () { showImage(currentIndex - 1); });
		}
		if (nextBtn) {
			nextBtn.addEventListener("click", function () { showImage(currentIndex + 1); });
		}

		var zoomInBtn = document.getElementById("lightboxZoomIn");
		var zoomOutBtn = document.getElementById("lightboxZoomOut");
		var zoomResetBtn = document.getElementById("lightboxZoomReset");
		if (zoomInBtn) {
			zoomInBtn.addEventListener("click", function () { setZoom(zoom + 0.5); });
		}
		if (zoomOutBtn) {
			zoomOutBtn.addEventListener("click", function () { setZoom(zoom - 0.5); });
		}
		if (zoomResetBtn) {
			zoomResetBtn.addEventListener("click", function () { setZoom(1); });
		}
		if (lightboxImage) {
			lightboxImage.addEventListener("dblclick", function () {
				setZoom(zoom > 1 ? 1 : 2);
			});
		}

		// Swipe left/right on the main image (and inside the lightbox)
		// to browse photos, the way phone users expect a gallery to work.
		// A swipe must not also trigger the lightbox open, so the click
		// right after a swipe is swallowed in the capture phase, before
		// Bootstrap's delegated click handler on document sees it.
		var justSwiped = false;
		function addSwipe(el, onSwipeLeft, onSwipeRight) {
			if (!el) {
				return;
			}
			var startX = null;
			var startY = null;
			el.addEventListener("touchstart", function (e) {
				var t = e.changedTouches[0];
				startX = t.clientX;
				startY = t.clientY;
			}, { passive: true });
			el.addEventListener("touchend", function (e) {
				if (startX === null) {
					return;
				}
				var t = e.changedTouches[0];
				var dx = t.clientX - startX;
				var dy = t.clientY - startY;
				startX = null;
				startY = null;
				if (Math.abs(dx) > 50 && Math.abs(dx) > Math.abs(dy) * 1.5) {
					justSwiped = true;
					setTimeout(function () { justSwiped = false; }, 300);
					if (dx < 0) {
						onSwipeLeft();
					} else {
						onSwipeRight();
					}
				}
			}, { passive: true });
		}
		addSwipe(mainThumbnail, function () { showImage(currentIndex + 1); }, function () { showImage(currentIndex - 1); });
		addSwipe(lightboxStage, function () { showImage(currentIndex + 1); }, function () { showImage(currentIndex - 1); });
		mainThumbnail.addEventListener("click", function (e) {
			if (justSwiped) {
				e.preventDefault();
				e.stopPropagation();
			}
		}, true);

		// Keyboard users: Enter / Space on the main image opens the lightbox.
		mainThumbnail.addEventListener("keydown", function (e) {
			if (e.key === "Enter" || e.key === " ") {
				e.preventDefault();
				mainThumbnail.click();
			}
		});

		// Arrow keys browse photos and +/- zooms while the lightbox is open.
		document.addEventListener("keydown", function (e) {
			if (!lightbox || !lightbox.classList.contains("show")) {
				return;
			}
			if (e.key === "ArrowLeft") {
				showImage(currentIndex - 1);
			} else if (e.key === "ArrowRight") {
				showImage(currentIndex + 1);
			} else if (e.key === "+" || e.key === "=") {
				setZoom(zoom + 0.5);
			} else if (e.key === "-") {
				setZoom(zoom - 0.5);
			}
		});

		// Keep the lightbox in sync with the image currently shown, even
		// if the main image was changed after the modal markup was rendered.
		if (lightbox) {
			lightbox.addEventListener("show.bs.modal", function () {
				setZoom(1);
				if (lightboxImage) {
					lightboxImage.src = images[currentIndex];
				}
				setActive(currentIndex);
			});
		}
	}

	// The script tag sits after the gallery markup, so the elements
	// normally already exist. If not (e.g. the markup moved above the
	// script), fall back to DOMContentLoaded once.
	if (document.getElementById("mainThumbnail")) {
		init();
	} else if (document.readyState === "loading") {
		document.addEventListener("DOMContentLoaded", init);
	}
})();
