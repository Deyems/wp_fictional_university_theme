import $ from "jquery";

class Search{
    constructor(){
        this.addSearchHTML();
        this.resultsDiv = $(".search-overlay__results");
        this.openButton = $(".js-search-trigger");
        this.closeButton = $(".search-overlay__close");
        this.searchOverlay = $(".search-overlay");
        this.searchField = $("#search-item");
        this.skeyNumber = 83;
        this.escKeyNumber = 27;
        this.events();
        this.isOverLayOpen = false;
        this.isSpinnerVisible = false;
        this.previousVal;
        this.typingTimer;
    }


    //Call to Events
    events(){
        this.openButton.on("click", this.openOverlay.bind(this));
        this.closeButton.on("click", this.closeOverlay.bind(this));
        $(document).on("keydown", this.keyPressDispatcher.bind(this));
        this.searchField.on("keyup", this.searchDB.bind(this));
    }

    //Methods
    searchDB(){
        if(this.previousVal !== this.searchField.val()){
            clearTimeout(this.typingTimer);

            if(this.searchField.val()){
                //Add a loader after typing before showing results
                if(!this.isSpinnerVisible){
                    this.resultsDiv.html("<div class='spinner-loader'></div>");
                    this.isSpinnerVisible = true;
                }
                this.typingTimer = setTimeout(this.getResults.bind(this),2000);
            }else{
                this.resultsDiv.html('');
            }

            
        }
        this.previousVal = this.searchField.val();
    }

    getResults(){
        //UniversityData object is created by extending PHP WP
        //The Javascript file is enqueued and then localized so as to
        // add objects to the script
        let url = universityData.root_url + "/wp-json/wp/v2/posts?search="+ this.searchField.val();
        let url2 = universityData.root_url + "/wp-json/wp/v2/pages?search="+ this.searchField.val();
        
        //Asynchronous API calls
        $.when($.getJSON(url),$.getJSON(url2))
        .then((posts, pages) => {
            let combined = posts[0].concat(pages[0]);
            this.resultsDiv.html(`
                <h2 class="search-overlay__section-title">General Information</h2>
                ${combined.length ? '<ul class="link-list min-list">' : '<p>No results match was found</p>'}
                ${combined.map(item => `<li><a href="${item.link}">${item.title.rendered}</a> ${item.type== 'posts' ? `by ${item.author_name}` : ''}</li>`).join('')}
                ${combined.length ? '</ul>' : ''}
            `)
        },() => {
            this.resultsDiv.html('<p>Unexpected Error occured</p>');
        });
        // $.getJSON(url, results => {
        //     this.resultsDiv.html(`
        //         <h2 class="search-overlay__section-title">General Information</h2>
        //         ${results.length ? '<ul class="link-list min-list">' : '<p>No results match was found</p>'}
        //         ${results.map(item => `<li><a href="${item.link}">${item.title.rendered}</a></li>`).join('')}
        //         ${results.length ? '</ul>' : ''}
        //     `);
        // });
        this.isSpinnerVisible = false;
    }

    openOverlay(){
        this.searchOverlay.addClass("search-overlay--active");
        $("body").addClass("body-no-scroll");
        this.searchField.val('');
        setTimeout(() => this.searchField.focus(), 302);
        this.isOverLayOpen = true;
    }
    
    closeOverlay(){
        this.searchOverlay.removeClass("search-overlay--active");
        $("body").removeClass("body-no-scroll");
        this.isOverLayOpen = false;
    }

    keyPressDispatcher(e){
        if(e.keyCode == this.skeyNumber && !this.isOverLayOpen && !$("input, textarea").is(':focus')){
            this.openOverlay();
        }

        if(e.keyCode == this.escKeyNumber && this.isOverLayOpen){
            this.closeOverlay();
        }
    }

    addSearchHTML(){
        $("body").append(`
        <div class="search-overlay">
            <div class="search-overlay__top">
                <div class="container">
                    <i class="fa fa-search search-overlay__icon" aria-hidden="true"></i>
                    <input type="text" class="search-term" placeholder="Enter what you are looking for?" id="search-item">
                    <i class="fa fa-window-close search-overlay__close" aria-hidden="true"></i>
                </div>
            </div>
            
            <div class="container">
                <div class="search-overlay__results">
                </div>
            </div>

        </div>
        `);
    }
}
export default Search;