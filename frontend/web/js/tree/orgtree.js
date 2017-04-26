function init() {
    if (window.goSamples) goSamples();  // init for these samples -- you don't need to call this
    var $ = go.GraphObject.make;  // for conciseness in defining templates
 
    myDiagram =
      $(go.Diagram, "myDiagram", // must be the ID or reference to div
        {
          initialContentAlignment: go.Spot.Center,
          // make sure users can only create trees
          validCycle: go.Diagram.CycleDestinationTree,
          // users can select only one part at a time
          maxSelectionCount: 1,
          layout:
            $(go.TreeLayout,
              {
                treeStyle: go.TreeLayout.StyleLastParents,
                arrangement: go.TreeLayout.ArrangementHorizontal,
                // properties for most of the tree:
                angle: 90,
                layerSpacing: 35,
                // properties for the "last parents":
                alternateAngle: 90,
                alternateLayerSpacing: 35,
                alternateAlignment: go.TreeLayout.AlignmentBus,
                alternateNodeSpacing: 20
              }),
          // support editing the properties of the selected person in HTML
          "ChangedSelection": onSelectionChanged,
          "TextEdited": onTextEdited,
          // enable undo & redo
          "undoManager.isEnabled": true
        });

    // when the document is modified, add a "*" to the title and enable the "Save" button
   /* myDiagram.addDiagramListener("Modified", function(e) {
      var button = document.getElementById("SaveButton");
      if (button) button.disabled = !myDiagram.isModified;
      var idx = document.title.indexOf("*");
      if (myDiagram.isModified) {
        if (idx < 0) document.title += "*";
      } else {
        if (idx >= 0) document.title = document.title.substr(0, idx);
      }
    });*/

    var levelColors = ["#AC193D/#BF1E4B", "#2672EC/#2E8DEF", "#8C0095/#A700AE", "#5133AB/#643EBF",
                       "#008299/#00A0B1", "#D24726/#DC572E", "#008A00/#00A600", "#094AB2/#0A5BC4"];

    // override TreeLayout.commitNodes to also modify the background brush based on the tree depth level
    myDiagram.layout.commitNodes = function() {
      go.TreeLayout.prototype.commitNodes.call(myDiagram.layout);  // do the standard behavior
      // then go through all of the vertexes and set their corresponding node's Shape.fill
      // to a brush dependent on the TreeVertex.level value
      myDiagram.layout.network.vertexes.each(function(v) {
        if (v.node) {
          var level = v.level % (levelColors.length);
          var colors = levelColors[level].split("/");
          var shape = v.node.findObject("SHAPE");
          if (shape) shape.fill = $(go.Brush, go.Brush.Linear, { 0: colors[0], 1: colors[1], start: go.Spot.Left, end: go.Spot.Right });
        }
      });
    }

    // when a node is double-clicked, add a child to it
    function nodeDoubleClick(e, obj) {
      var clicked = obj.part;
      if (clicked !== null) {
        var thisemp = clicked.data;
        if(thisemp.child>=2){
        	alert('改用户下不能再放用户');
        	return;
        }
        myDiagram.startTransaction("add employee");
        thisemp.child=parseInt(thisemp.child+1);
        var nextkey = (myDiagram.model.nodeDataArray.length + 1).toString();
        var newemp = { key: nextkey, name: "姓名", register_type: "",rank:"",child:"",position:getPosition(thisemp),level:parseInt(thisemp.level+1), parent: thisemp.key };
        myDiagram.model.addNodeData(newemp);
        myDiagram.commitTransaction("add employee");
        
        /*thisemp.startTransaction("modified child");
        thisemp.setDataProperty(data, "child", thisemp.child+1);
        thisemp.commitTransaction("modified child" );*/
      }
    }

    function getPosition(thisemp){
    	if(thisemp.child==1)
    		return "left";
    	return "right";
    	}
    
    // this is used to determine feedback during drags
    function mayWorkFor(node1, node2) {
      if (!(node1 instanceof go.Node)) return false;  // must be a Node
      if (node1 === node2) return false;  // cannot work for yourself
      if (node2.isInTreeOf(node1)) return false;  // cannot work for someone who works for you
      return true;
    }

    // This function provides a common style for most of the TextBlocks.
    // Some of these values may be overridden in a particular TextBlock.
    function textStyle() {
      return { font: "9pt  Segoe UI,sans-serif", stroke: "white" };
    }

    // This converter is used by the Picture.
    function findHeadShot(key) {
      return "../../../upload/avatar/unknown.jpg"
    };


    // define the Node template
    myDiagram.nodeTemplate =
      $(go.Node, "Auto",
        { doubleClick: nodeDoubleClick },
        { // handle dragging a Node onto a Node to (maybe) change the reporting relationship
          mouseDragEnter: function (e, node, prev) {
            var diagram = node.diagram;
            var selnode = diagram.selection.first();
            if (!mayWorkFor(selnode, node)) return;
            var shape = node.findObject("SHAPE");
            if (shape) {
              shape._prevFill = shape.fill;  // remember the original brush
              shape.fill = "darkred";
            }
          },
          mouseDragLeave: function (e, node, next) {
            var shape = node.findObject("SHAPE");
            if (shape && shape._prevFill) {
              shape.fill = shape._prevFill;  // restore the original brush
            }
          },
          mouseDrop: function (e, node) {
            var diagram = node.diagram;
            var selnode = diagram.selection.first();  // assume just one Node in selection
            if (mayWorkFor(selnode, node)) {
              // find any existing link into the selected node
              var link = selnode.findTreeParentLink();
              if (link !== null) {  // reconnect any existing link
                link.fromNode = node;
              } else {  // else create a new link
                diagram.toolManager.linkingTool.insertLink(node, node.port, selnode, selnode.port);
              }
            }
          }
        },
        // for sorting, have the Node.text be the data.name
        new go.Binding("text", "name"),
        // bind the Part.layerName to control the Node's layer depending on whether it isSelected
        new go.Binding("layerName", "isSelected", function(sel) { return sel ? "Foreground" : ""; }).ofObject(),
        // define the node's outer shape
        $(go.Shape, "Rectangle",
          {
            name: "SHAPE", fill: "white", stroke: null,
            // set the port properties:
            portId: "", fromLinkable: true, toLinkable: true, cursor: "pointer"
          }),
        $(go.Panel, "Horizontal",
          $(go.Picture,
            {
              maxSize: new go.Size(39, 50),
              margin: new go.Margin(6, 8, 6, 10),
            },
            new go.Binding("source", "key", findHeadShot)),
          // define the panel where the text will appear
          $(go.Panel, "Table",
            {
              maxSize: new go.Size(150, 999),
              margin: new go.Margin(6, 10, 0, 3),
              defaultAlignment: go.Spot.Left
            },
            $(go.RowColumnDefinition, { column: 2, width: 4 }),
            $(go.TextBlock, textStyle(),  // the name
              {
                row: 0, column: 0, columnSpan: 5,
                font: "12pt Segoe UI,sans-serif",
                editable: true, isMultiline: false,
                minSize: new go.Size(10, 16)
              },
              new go.Binding("text", "name").makeTwoWay()),
            
            $(go.TextBlock, textStyle(),
              {
                row: 1, column: 1, columnSpan: 4,
                editable: true, isMultiline: false,
                minSize: new go.Size(10, 14),
                margin: new go.Margin(0, 0, 0, 3)
              },
              new go.Binding("text", "register_type").makeTwoWay()),
              
            $(go.TextBlock, textStyle(),
              { row: 2, column: 1, columnSpan: 4,
                editable: true, isMultiline: false,
                minSize: new go.Size(10, 14),
                margin: new go.Margin(0, 0, 0, 3)
            },
              new go.Binding("text", "rank").makeTwoWay())
                 
          )  // end Table Panel
        ) // end Horizontal Panel
      );  // end Node

    // define the Link template
    myDiagram.linkTemplate =
      $(go.Link, go.Link.Orthogonal,
        { corner: 5, relinkableFrom: true, relinkableTo: true },
        $(go.Shape, { strokeWidth: 4, stroke: "#00a4a4" }));  // the link shape

    // read in the JSON-format data
    load();
  }
	
  // Allow the user to edit text when a single node is selected
  function onSelectionChanged(e) {
    var node = e.diagram.selection.first();
    if (node instanceof go.Node) {
    	if(node.data.name=='姓名')
    		updateProperties(node.data);
    	else{
    		updateProperties(null);
    	}
    } else {
      updateProperties(null);
    }
  }

  // Update the HTML elements for editing the properties of the currently selected node, if any
  function updateProperties(data) {
    if (data === null) {
      document.getElementById("propertiesPanel").style.display = "none";
      document.getElementById("name").value = "";
 
    } else {
      document.getElementById("propertiesPanel").style.display = "block";
      document.getElementById("name").value = data.name || ""; 
    }
  }
  // This is called when the user has finished inline text-editing
  function onTextEdited(e) {
    var tb = e.subject;
    if (tb === null || (tb.name!=='姓名')) return;
    var node = tb.part;
    if (node instanceof go.Node) {
      updateProperties(node.data);
    }
  }

  // Update the data fields when the text is changed
  function updateData() {
    var node = myDiagram.selection.first();
    var name=$('#name option:selected').text();
    var key=$('#name option:selected').val();
    var register_type=$('#name option:selected').attr("register_type");
    var rank=$('#name option:selected').attr("rank");
    $('#name option:selected').remove();
   // alert(name);
    // maxSelectionCount = 1, so there can only be one Part in this collection
    var data = node.data;
    if (node instanceof go.Node && data !== null) {
      var model = myDiagram.model;
      model.startTransaction("modified name");
      model.setDataProperty(data, "name", name);
      model.commitTransaction("modified name" );
      model.startTransaction("modified key");
      model.setDataProperty(data, "key", key);
      model.commitTransaction("modified key" );
      model.startTransaction("modified register_type");
      model.setDataProperty(data, "register_type", register_type);
      model.commitTransaction("modified register_type" );
      model.startTransaction("modified rank");
      model.setDataProperty(data, "rank", rank);
      model.commitTransaction("modified rank" );

    }
  }

  // Show the diagram's model in JSON format
  function save() {
    document.getElementById("mySavedModel").value = myDiagram.model.toJson();
    myDiagram.isModified = false;
    $.ajax({
    	 	url:'updaterelationtree',// 跳转到 action  
    	
    	    data:{"arr":myDiagram.model.toJson()},    
    	    type:'post',    
    	    cache:false,      
    	    success:function(data) {  
    	    	alert(data);
    	        if(data=="success" ){    
    	            // view("修改成功！");    
    	            alert("修改成功！");    
    	            window.location.reload();    
    	        }else{    
    	        	 alert("修改失败！");     
    	        }    
    	     },    
    	     error : function() {    
    	          // view("异常！");    
    	          alert("服务器异常！");    
    	     }    
    	
    });
    
  }
  function load() {
    myDiagram.model = go.Model.fromJson(document.getElementById("mySavedModel").value);
  }
  
  var nodeData={ "class": "go.TreeModel",
		  "nodeDataArray":			 
			   [
		{"key":"1", "name":"Stella Payne Diaz", "register_type":"白金会员","user_level":"DR高级",'child':'2'},
		{"key":"2", "name":"Luke Warm", "register_type":"白金会员","user_level":"DR高级", "parent":"1",'child':'2','position':'l','level':'1'},
		{"key":"3", "name":"Meg Meehan Hoffa", "register_type":"白金会员","user_level":"DR高级", "parent":"2"},
		{"key":"4", "name":"Peggy Flaming",  "register_type":"白金会员","user_level":"DR高级", "parent":"1"},
		{"key":"5", "name":"Saul Wellingood",  "register_type":"白金会员","user_level":"DR高级", "parent":"4"},
		{"key":"6", "name":"Al Ligori",  "register_type":"白金会员","user_level":"DR高级", "parent":"2"},
		{"key":"7", "name":"Dot Stubadd",  "register_type":"白金会员","user_level":"DR高级", "parent":"3"},
		{"key":"8", "name":"Les Ismore",  "register_type":"白金会员","user_level":"DR高级", "parent":"5"},
		{"key":"9", "name":"April Lynn Parris",  "register_type":"白金会员","user_level":"DR高级", "parent":"6"},
		{"key":"10", "name":"Xavier Breath",  "register_type":"白金会员","user_level":"DR高级", "parent":"4"},
		{"key":"11", "name":"Anita Hammer",  "register_type":"白金会员","user_level":"DR高级", "parent":"5"},
		{"key":"12", "name":"Billy Aiken", "register_type":"白金会员","user_level":"DR高级", "parent":"10"},
		{"key":"13", "name":"Stan Wellback",  "register_type":"白金会员","user_level":"DR高级", "parent":"10"},
		{"key":"14", "name":"Marge Innovera",  "register_type":"白金会员","user_level":"DR高级", "parent":"10"},
		{"key":"15", "name":"Evan Elpus",  "register_type":"白金会员","user_level":"DR高级", "parent":"5"},
		{"key":"16", "name":"Lotta B. Essen", "register_type":"白金会员","user_level":"DR高级", "parent":"3"}
		 ]
		};
  
  window.load=init();