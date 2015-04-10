var Core = Core || {};

(function(Core) {

    "use strict";

	var Loading = Core.createComponent(function(properties, selector) {
		Core.Component.call(this, properties, {
			image: url("/images/ajax-loader.gif"),
			size: "50",
			depth: "2000",
			velocity: "fast",
			color: "whitesmoke"
		}, selector);
	});

    Loading.prototype.render = function() {
		var half = this.property("size") / 2,
			height = this.selector().outerHeight(false),
			width = this.selector().outerWidth(false);
		this.image = $("<img>", {
			css: {
				"position": "absolute",
				"width": half * 2,
				"height": half * 2,
				"left": "calc(50% - " + half + "px)",
				"margin-top": (this.selector().height() / 2 - half) + "px",
				"z-index": this.property("depth")
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
		return this.selector().animate({
			"opacity": 0.4
		}, this.property("velocity"));
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
		this.selector().animate({
			"opacity": 1
		}, this.property("velocity"));
    };

    Core.createPlugin("loading", function(selector, properties) {
		if (!$(selector).data("core-loading") || !$(selector).data("core-loading").image) {
			return Core.createObject(new Loading(properties, $(selector)), selector, true);
		} else {
			return void 0;
		}
	});

})(Core);