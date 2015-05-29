var assets = [
	config.url.templates + "js/classes/Home.js",
];

new Asset.javascripts(assets, 
{
    onComplete: function()
    {    
    	console.log("assets loaded");
    	var home = new Home();
    }
});