$(document).ready(function() {
	//Web10.Editing.init();
	//Web10.mainContainer = new Web10.MainContainer();
	
	var ioc = new Ioc();
	ioc.register(Web10.ContainerReg);
	Web10.ioc = ioc;
	
	var editing = new Web10.Editing(Web10.ioc);
	editing.init();
});
