Asset.javascripts = function(sources, options) {
    // load an array of js dependencies and fire events as it walks through
    options = Object.merge({
        onComplete: Function.from,
        onProgress: Function.from
    }, options);
    var counter = 0, todo = sources.length;

    var loadNext = function() {
        if (sources[0])
            source = sources[0];

        Asset.javascript(source, {
            onload: function() {
                counter++;
                options.onProgress.call(this, counter, source);
                sources.erase(source);

                if (counter == todo)
                    options.onComplete.call(this, counter);
                else
                    loadNext();
            }
        });
    };

    loadNext();
};