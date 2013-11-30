/*
---
Nested Elements: 
http://is.gd/TeaDrivenDesign

*/
Element.Properties.children = {
	get: function()      { return this.getChildren(); },
	set: function(value) { this.adopt(value);         } 
};
