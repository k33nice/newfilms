// 'use strict';

/**
 * URL parser
 * @type {[type]}
 */
var parser = document.createElement('a');
    parser.href = document.URL;

    parser.protocol;
    parser.hostname;
    parser.port;
    parser.pathname;
    parser.search;
    parser.hash;
    parser.host;

var url = parser.pathname;
/* End URl parser */

$("#limit").change(function(){
    $(".a").empty();
    $(".pagination").empty();
    indexFilms();
});

// init create film
$("input[name=add-request]").one("click",function() {
    createFilm();
});

$(document).ready(init);

function init() {
    if ( url == '/' ) {
        indexFilms();
    } else if ( url == '/import/' ) {
        $('#limit').remove();
        importFilm();
    } else if ( matched = url.match(/\/(\d+)/) ) {
        $('#limit').remove();
        var id = matched[1];
        showFilm(id);
    }
} //init()

function filmsGrid(page) {
    if ( !page ) page=1;
    var search = $('[name=search]', $('.search')).val();
    var limit = $('#limit').val();
    var sortOrder = 'desc';
    var params;
    var offset = (page-1)*limit;
    $('.onePage').eq(page-1).addClass('active');

    if(limit === 'all') {
        limit=100000;
        offset= 0;
        $('.pagination').empty();
    }
    if (search.length) {
        params = { Limit: limit, Search: search};
    } else {
        params = { Limit: limit, Offset: offset };
    };

    $.getJSON(
        "/api/films",
        params,
        function(dataResult) {
            var films = dataResult.Films;
            var grid = '';
            $.each(films, function(key, film) {
                grid +=
                    "<li id='" + film.Id + "'><a href='"+film.Id +"'><span>"+ film.Name + "</span></a>"+ "</li>"
            });

            $("<ul/>", {
                "class": "films",
                html: grid
            }).appendTo("div.a");
            if (search.length) {
                $('ul.films li').each(function(){
                    $(this).addClass('searched');
                });
            }
        });
    $(".search").submit(function() {
        $(".pagination").empty();
        $(".a").empty();
    });
    return false;
} //filmsGrid()

function indexFilms() {
    $.getJSON("/api/films/count/", function(dataResult) {
        var limit = $('#limit').val();
        var pageCount = Math.ceil(dataResult.TotalCount/limit);
        if (dataResult.TotalCount>limit) $('.pagination').text('Page:');
        for (var i=1; i<=pageCount; i++) {
            var span = '<span class="onePage">'+i+'</span>'
            $(span).appendTo('.pagination');
        }
            filmsGrid();
        $('.onePage').click(function() {
            page = $(this).text();
            $('.onePage').removeClass('active');
            $('.a').empty();
            filmsGrid(page);
        });
    });
    return false;
} //indexFilms()

function showFilm(id) {
    $('.pagination').empty();
    $.getJSON("/api/films/" + id, function(dataResult) {
        var film = dataResult.Film; // rename to film
        var html =
            "<li id='" + film.Id + "'>"+
                    "<span>"+film.Name+"</span><span>"+film.Year+"</span><span>"+film.Format+"</span>"+
                    "<span>"+film.Stars+"</span>"+
                    "<span class='delete'>DELETE</span>"+
           "</li>";

        $("<ul/>", {
            "class": "films",
            html: html
        }).appendTo("div.a");

        // init delete action
        $(document).on('click', 'span.delete', function() {
            $.ajax({
                url: '/api/films/'+film.Id,
                type: 'delete',
                success: function() {
                    $("#message").text("deleted");
                }
            });
        });
    });
    $('input').remove('.create');
} //showFilms()

// function deleteFilm(id) {

// }

function createFilm() {
    $('.pagination').empty();
    $('<form class="add" onsubmit="return postFilm();"></form>').appendTo('#handler');
    $('<input name="Name" type="text" value="title">').appendTo('.add');
    $('<input name="Year" type="text" value="year">').appendTo('.add');
    $('<input name="Format" type="text" value="format">').appendTo('.add');
    $('<input name="ActorName" type="text" value="actors">').appendTo('.add');
    $('<input type="submit" vlaue="добавить">').appendTo('.add');

    /*Clear inputs*/
    $('input:text').bind('focus', function() {
        $(this).val('');
    });

    $(".add").submit(function() {
        $(".films").empty();
    });
} //createFilm()

function postFilm() {
    var data = $('.add').serialize();
    $.ajax({
        url: '/api/films/',
        type: 'post',
        data: data,
        success: function(data) {
            $('.a').html(data);
            $('#message').text('create successful');
        }
    });
    return false;
} //postFilm()

/*function updateFile(id) {

}*/

function importFilm() {
    $('.pagination').empty();
    $('<form onsubmit="return uploadFile();" id="upload"></form>').appendTo('#handler');
    $('<input id="file" name="file", type="file" /><input type="submit" value="upload">').appendTo('#upload');
    $('<form onsubmit="return importFile();" id="import"></form>').appendTo('#handler');
    $('<input type="submit" value="import">').appendTo('#import');
} //importFIlm()

function uploadFile() {
    var file_data = $('#file').prop('files')[0];
    var form_data = new FormData();
    form_data.append('file', file_data)
    $.ajax({
            url: '../upload.php',
            dataType: 'text',
            contentType: false,
            processData: false,
            data: form_data,
            type: 'post',
            success: function(){
                $("#message").text("upload successful")
            }
    });
    return false;
} //uploadFile()

function importFile() {
    $.ajax({
        url: '/api/films/import/',
        type: 'post',
        success: function() {
            $('#message').text('import successful')
        }
    });
    return false;
} //importFile()