var Ioc = function() {
	this.values = {};
	this.parent = null;
	this.root = this;
};

Ioc.prototype.set = function(id, value) {
	this.values[id] = value;
	return this;
};

Ioc.prototype.setAll = function(values) {
	for (name in values) {
		this.set(name, values[name]);
	}
	return this;
};

Ioc.prototype.get = function(id) {
	//if ((this.values[id] == null) && (this.parent != null)) {
	//	return this.parent.get(id);
	
	var c = this;
	while ((c.values[id] == null) && (c.parent != null)) {
		c = c.parent;
	}
	
	if (c.values[id] instanceof Ioc) {
		return c.values[id].get(id);
	} else if (typeof c.values[id] === 'function') {
		return c.values[id].call(this, this);
	} else {
		return c.values[id];
	}
	return null;
};

Ioc.prototype.raw = function(id) {
	return this.values[id];
};

Ioc.prototype.singleton = function(func) {
	var wrap = function(ioc) {
		if (func.object == null) {
			func.object = func(ioc);
		}
		return func.object;
	};
	return wrap;
};

Ioc.prototype.callable = function(func) {
	var wrap = function(ioc) {
		return func;
	};
	return wrap;
};

Ioc.prototype.child = function(id) {
	var c = new Ioc();
	c.parent = this;
	c.root = this.root;
	this.values[id] = c;
	return c;
};

Ioc.prototype.register = function(ns) {
	for (cls in ns) {
		var c = new ns[cls](this);
		c.register();
	}
};

//Ioc.prototype.childget = function(id, deps) {
//	var c = this.child();
//	c.setAll(deps);
//	return c.get(id);
//};

//Ioc.prototype.factory = function(deps) {
//var c = this.child();
//c.setAll(deps);
//return c;
//};

var RuntimeFactory = function(cls, deps) {
	this.cls = cls;
	this.deps = deps || {};
};

RuntimeFactory.prototype.get = function(runtimeDeps) {
	for (name in runtimeDeps) {
		this.deps[name] = runtimeDeps[name];
	}
	return new this.cls(this.deps);
};
