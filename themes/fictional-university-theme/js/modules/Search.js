import $ from "jquery";

class Search{
    constructor(){
        // this.openButton = document.querySelector('.js-search-trigger');
        // this.closeButton = document.querySelector('.search-overlay__close');
        // this.searchOverlay = document.querySelector('.search-overlay');
        this.resultsDiv = $(".search-overlay__results");
        this.openButton = $(".js-search-trigger");
        this.closeButton = $(".search-overlay__close");
        this.searchOverlay = $(".search-overlay");
        this.searchField = $("#search-item");
        this.skeyNumber = 83;
        this.escKeyNumber = 27;
        this.events();
        this.isOverLayOpen = false;
        this.typingTimer;
    }


    //Call to Events
    events(){
        this.openButton.on("click", this.openOverlay.bind(this));
        this.closeButton.on("click", this.closeOverlay.bind(this));
        $(document).on("keydown", this.keyPressDispatcher.bind(this));
        this.searchField.on("keydown", this.searchDB.bind(this));
    }

    //Methods
    searchDB(){
        // console.log('Javascript typing is cool!');
        clearTimeout(this.typingTimer);
        this.typingTimer = setTimeout(this.getResults.bind(this),2000);
    }

    getResults(){
        // console.log('My belle o');
        this.resultsDiv.html("Imageing we are here now!");
    }

    openOverlay(){
        this.searchOverlay.addClass("search-overlay--active");
        $("body").addClass("body-no-scroll");
        // console.log('Open just ran');
        this.isOverLayOpen = true;
    }
    
    closeOverlay(){
        this.searchOverlay.removeClass("search-overlay--active");
        $("body").removeClass("body-no-scroll");
        // console.log('Close just ran');
        this.isOverLayOpen = false;
    }

    keyPressDispatcher(e){
        // console.log('Body is shaking'+ e.keyCode);
        if(e.keyCode == this.skeyNumber && !this.isOverLayOpen){
            this.openOverlay();
        }

        if(e.keyCode == this.escKeyNumber && this.isOverLayOpen){
            this.closeOverlay();
        }
    }
}

export default Search;