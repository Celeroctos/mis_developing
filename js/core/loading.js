var Core = Core || {};

(function(Core) {

    "use strict";

	var Loading = Core.createComponent(function(properties, selector) {
		Core.Component.call(this, properties, {
			image: url("/images/ajax-loader.gif"),
			size: "50",
			depth: "200",
			velocity: "fast"
		}, selector);
	});

    Loading.prototype.render = function() {
		var half = this.property("size") / 2;
		this.image = $("<img>", {
			css: {
				"position": "absolute",
				"width": this.property("size"),
				"height": this.property("size"),
				"left": "calc(50% - " + half + "px)",
				"z-index": this.property("depth")
			},
			src: this.property("image")
		});
		this.selector().before(this.back = $("<div>", {
			css: {
				"width": this.selector().width(),
				"height": this.selector().height(),
				"position": "absolute",
				"background-color": "whitesmoke",
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