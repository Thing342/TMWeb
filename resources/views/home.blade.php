@extends('app')

@section('title', 'Home')

@section('content')

    <section class="my-4">
        <h1 class="display-4">Travel Mapping</h1>
        <p class="lead">
            Tracking Cumulative Travels
        </p>
    </section>

    <section class="my-4">
        <h2>Welcome to Travel Mapping</h2>
        <hr>
        <p id="p-welcome-msg">
            Travel Mapping is a collaborative project implemented and maintained by a group of travel enthusiasts who
            enjoy tracking their cumulative highway travels. This site allows its users to submit lists of highway
            segments they've traveled on the highway systems that have been included in the project. Those lists are
            then imported into the project's database, to be included along with other users' stats and maps.
        </p>
    </section>

    <section class="my-4">
        <h2>Travel Mapping Highway Data</h2>
        <hr>
        <p id="p-status-msg">
            Travel Mapping currently includes highway data for 211 "active" systems. Active systems are those which we
            believe are accurate and complete, and for which any changes that affect users will be noted in the highway
            data updates table. An additional 66 systems are in "preview" status, which means they are substantially
            complete, but still undergoing final revisions. These may still undergo significant changes without
            notification. 17 more are in development but are not yet complete. These "devel" systems are not yet
            included in stats or plotted on user maps. Active systems encompass 24,866 routes for 794,635 miles of
            "clinchable" highways, and that expands to 41,582 routes for 1,117,623 miles when preview systems are
            included. </p>
    </section>

    <section class="my-4">
        <h2>How to Participate</h2>
        <hr>
        <p>
            Anyone can submit their travels to be included in the site. Please see the information in the project forum
            for how to create and submit your data.
        </p>
        <p>
            Once your data is in the system, you will be listed on the main traveler stats page, and you can see a
            summary of your travels on your user page. Click around on the various links and table entries to find more
            ways to see your travels, both as tabular statistics and plotted on maps.
        </p>
        <p>
            Some experienced users volunteer to help the project. If this interests you, start by reporting problems
            with existing highway data. Those who have learned the project's structure and highway data rules and
            guidelines can help greatly by providing review of new highway systems in development. Highly experienced
            users can learn how to plot new highway systems under the guidance of experienced contributors. Again, see
            the project forum for more information.
        </p>
        <p>
            Project news is also posted on the Travel Mapping Twitter feed. Follow us!
        </p>
    </section>

    <section class="my-4">
        <h2>What's New with Highway Data</h2>
        <hr>
        <p>
            Highway data is updated almost daily as corrections are made and progress is made on systems in development.
            When a highway system is deemed correct and complete to the best of our knowledge, it becomes "active". Here
            are the newest systems to become active, with their activation dates:
        </p>
        <!-- TODO: Live Content -->
        <ul id="updated-systems">
            <li>Faroe Islands Landsvegur (frolv), 2018-05-03</li>
            <li>Alaska State Highways (usaak), 2018-03-23</li>
            <li>Slovakia Cesta II. Triedy (svkii), 2018-03-11</li>
            <li>Slovenia Regionalna Ceste (svnr), 2018-03-11</li>
            <li>Korean Expressways (korex), 2018-02-09</li>
            <li>Lithuania Krašto Keliai (ltuk), 2018-01-22</li>
            <li>Estonia Tugimaanteed (estt), 2018-01-05</li>
            <li>Montana Secondary State Highways (usamts), 2018-01-05</li>
        </ul>
    </section>

@endsection