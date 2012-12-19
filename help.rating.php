<h2>How does the algorithm work</h2>
<p>In order to support the developer in choosing the appropiate web services for his mashup we developed several ranking mechanisms.
 Any set of web services matched to a specific search provided to the developer in a list that shows its overall importance within the set of web services.
 <br/><center><img src="images/help/rating/figure1.png" /></center>
 </p>
 
 
<p>To explain the different mechanisms. Let S be the set of web services whereas M is the set of mashups in out dataset.
 We reduced the intuitive hypergraph to an undirected and bipartite graph <pre>G = (V,E)</pre> letting the set of vertices <pre>V = S U M</pre> be the unon of S and M.
 The set of edges was defined as <pre>E = { (s,m) | Mashup m uses the API of service s.}</pre> The next figure illustrates the structure of this graph:
  <br/><center><img src="images/help/rating/figure2.png" /></center>
 </p>
 
 <p>In particular, the following raking functions have been developed:
 
	 <ul>	 
		<li>
			<i>Degree centrality</i>, is defined as the number of links incident upon a node.In our scenario the number of mashups that use a certain service, which directly reflects the popularity.
			<br/><center><img src="images/help/rating/figure3.png" /></center>
		</li>

		<li>
			<i>Closeness centrality</i>, is a natural distance metric between all pairs of nodes, defined by the length of their shortest paths.
			 The farness of a node s is defined as the sum of its distances to all other nodes, and its closeness is defined as the inverse of the farness.
			 Thus, the more central a node is the lower its total distance to all other nodes. Closeness can be regarded as a measure of how fast it will take to spread information from s to all other nodes sequentially.
			 Implemented in our case with an extension as (Opsahl, Agneessens and Skvoretz 2010) propose.
			 A vertex v is ranked higher the shorter the geodesic distance between itself and other verticesusing the algorithm Djisktra.
			 Closeness centrality is also an important technique in social network analysis.
			 <br/><center><img src="images/help/rating/formula1.png" /></center>
			 <br/><center><img src="images/help/rating/figure4.png" /></center>
		 
		 </li>
		 
		<li>
			<i>Social centrality</i>, taking into account the different social (Twitter, Facebook).
			 We have implemented a ranking that sorts the different mashups and APIs, by popularity in these networks. To do this we explore the number of tweets and likes related to the different sources.
			<br/><center><img src="images/help/rating/formula2.png" /></center>
			<br/><center><img src="images/help/rating/figure5.png" /></center>
		 </li>
	 </ul>
 </p>
