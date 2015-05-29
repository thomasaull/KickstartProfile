var Home = new Class(
{	
	Implements: Options,
	//Extends: ,
	//Binds: ['myFunction'],
	
	options: {
		option: false
	},
	
	classVariable: undefined,
	
	initialize: function(container, options)
	{		
		this.setOptions(options);
		// do some other stuff
		
		console.log("init of class Home");
	}
		
});