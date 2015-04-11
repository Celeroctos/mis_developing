var Core = Core || {};

(function(Core) {

    "use strict";

	var Loading = Core.createComponent(function(properties, selector) {
		Core.Component.call(this, properties, {
			image: url("/images/ajax-loader.gif"),
			depth: 1000,
			size: 50,
			velocity: "fast",
			color: "lightgray"
		}, selector);
	});

    Loading.prototype.render = function() {
		var half = this.property("size") / 2,
			height = this.selector().outerHeight(false),
			width = this.selector().outerWidth(false),
			index;
		if (this.hasOwnProperty("image")) {
			this.reset();
		}
		if (!(index = parseInt(this.selector().css("z-index")))) {
			index = this.property("depth");
		} else {
			index += 1;
		}
		this.image = $("<img>", {
			css: {
				"position": "absolute",
				"width": half * 2,
				"height": half * 2,
				"left": "calc(50% - " + half + "px)",
				"margin-top": (height / 2 - half) + "px",
				"z-index": index
			},
			src: this.property("image")
		});
		this.selector().before(this.back = $("<div>", {
			css: {
				"width": width,
				"height": height,
				"position": "absolute",
				"background-color": this.property("color"),
				"opacity": "0.5",
				"z-index": "100"
			}
		}).addClass(this.selector().attr("class")).fadeIn(this.property("velocity"))).before(
			this.image.fadeIn(this.property("velocity"))
		);
    };

	Loading.prototype.update = function() {
		this.render();
	};

	Loading.prototype.destroy = function() {
		this.reset();
	};

    Loading.prototype.reset = function() {
		if (this.image) {
			this.image.fadeOut(this.property("velocity"));
		}
		if (this.back) {
			this.back.fadeOut(this.property("velocity"));
		}
    };

    Core.createPlugin("loading", function(selector, properties) {
		if (!$(selector).data("core-loading") || !$(selector).data("core-loading").image) {
			return Core.createObject(new Loading(properties, $(selector)), selector, true);
		} else {
			return void 0;
		}
	});

})(Core);