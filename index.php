<html>
    <head>
        <title>
            
        </title>
        <style type="text/css">
            @import 'root.css';
        </style>
        <?php
        
            $size=array('w'=>21,'h'=>21);

            $mazeCells=array(
                'TR'=>createMazeCellType('img/maze/corner_tr.bmp',true,false,false,true),
                'TL'=>createMazeCellType('img/maze/corner_tl.bmp',true,true,false,false),
                'BR'=>createMazeCellType('img/maze/corner_br.bmp',false,false,true,true),
                'BL'=>createMazeCellType('img/maze/corner_bl.bmp',false,true,true,false),
                );
                
            $mazeMoves=array(
                'U'=>array('x'=>0,'y'=>-1),
                'D'=>array('x'=>0,'y'=>1),
                'L'=>array('x'=>-1,'y'=>0),
                'R'=>array('x'=>1,'y'=>0),
                );
                
            function createMazeCellType($path,$top,$left,$bottom,$right){
                return array('path'=>$path,'edges'=>array('top'=>$top,'bottom'=>$bottom,'left'=>$left,'right'=>$right));
            }
            
            function writeMaze(&$maze,$pos,$move){
                global $size;
                global $mazeMoves;
            
                $newPos=array('x'=>($pos['x']+$move['x']),'y'=>($pos['y']+$move['y']));
                if($newPos['x']<0||$newPos['x']>=$size['w']) return;
                if($newPos['y']<0||$newPos['y']>=$size['h']) return;
                if($maze[$newPos['y']][$newPos['x']]!=null) return;
            
                $next=nextMazeCell($move);
                if($next['key']=='TR'&&($move==$mazeMoves['D'])){
                    $move=$mazeMoves['R'];
                }else if($next['key']=='TR'&&($move==$mazeMoves['L'])){
                    $move=$mazeMoves['U'];
                }else if($next['key']=='TL'&&($move==$mazeMoves['R'])){
                    $move=$mazeMoves['U'];
                }else if($next['key']=='TL'&&($move==$mazeMoves['D'])){
                    $move=$mazeMoves['L'];
                }else if($next['key']=='BR'&&($move==$mazeMoves['U'])){
                    $move=$mazeMoves['R'];
                }else if($next['key']=='BR'&&($move==$mazeMoves['L'])){
                    $move=$mazeMoves['D'];
                }else if($next['key']=='BL'&&($move==$mazeMoves['U'])){
                    $move=$mazeMoves['L'];
                }else if($next['key']=='BL'&&($move==$mazeMoves['R'])){
                    $move=$mazeMoves['D'];
                }
            
                $maze[$newPos['y']][$newPos['x']]=$next['value']['path'];
                
                writeMaze($maze,$newPos,$move);  
            }
            
            function nextMazeCell($pathMove){
                global $mazeCells;
                
                $cellChoices;
                if($pathMove['x']==1){
                    $cellChoices=array_filter($mazeCells,function ($val){return $val['edges']['left'];});
                }else if($pathMove['x']==-1){
                    $cellChoices=array_filter($mazeCells,function($val){return $val['edges']['right'];});
                }else if($pathMove['y']==1){
                    $cellChoices=array_filter($mazeCells,function($val){return $val['edges']['top'];});
                }else if($pathMove['y']==-1){
                    $cellChoices=array_filter($mazeCells,function($val){return $val['edges']['bottom'];});
                }
                $key=array_rand($cellChoices);
                return array('key'=>$key,'value'=>$mazeCells[$key]);
            }
            
            function initializeMaze(&$maze){
                global $size;
                for($row=0;$row<$size['h'];$row++){
                    array_push($maze,array());
                    for($col=0;$col<$size['w'];$col++){
                        array_push($maze[$row],null);
                    }
                }
            }

            function createMaze(){
                global $mazeMoves;
                global $size;
                
                $maze=array();
                initializeMaze($maze);
                
                $maze[10][10]=createMazeCellType('img/maze/terminator.bmp',true,true,true,true)['path'];
                
                $move=$mazeMoves['U'];
                $pos=array('x'=>10,'y'=>10);
                writeMaze($maze,$pos,$move);
                   
                $move=$mazeMoves['D'];
                $pos=array('x'=>10,'y'=>10);
                writeMaze($maze,$pos,$move);
                
                $move=$mazeMoves['L'];
                $pos=array('x'=>10,'y'=>10);
                writeMaze($maze,$pos,$move);
                
                $move=$mazeMoves['R'];
                $pos=array('x'=>10,'y'=>10);
                writeMaze($maze,$pos,$move);
                
                for($row=0;$row<$size['h'];$row++){
                    for($col=0;$col<$size['w'];$col++){
                        
                        if($maze[$row][$col]==null){
                            $maze[$row][$col]='img/maze/blank.bmp';
                        }
    
                    }
                }
                return $maze;
            }
            
            function renderMazeCell($path){
                $mazeCell="<div class='MazeCell'>";
                $mazeCell.="<img class='MazeCell' src='$path'/>";
                $mazeCell.="</div>";
                return $mazeCell;
            }
            
            function renderMaze($maze){
                global $size;
                $mazeDiv="<div class='Maze'>";
                for($row=0;$row<$size['h'];$row++){
                    $mazeRow="<div class='MazeRow'>";
                    for($col=0;$col<$size['w'];$col++){
              
                        $mazeRow.=renderMazeCell($maze[$row][$col]);
                    }
                    
                    $mazeRow.="</div>";
                    $mazeDiv.=$mazeRow;
                }
                $mazeDiv.="</div>";
                return $mazeDiv;
            }
        ?>
        
        <body>
            <div class='GlobalContainer'>
                <div class='Header'>Quadrapus Generator</div>
                <div class='ContentContainer'>
                    <div class='TextContainer'>
                      <div class='TextContent'>
                        Welcome to the Quadrapus generator, the only generator that tells you it generates quadrapusus <Flark>(because it does)</Flark>. Press the button to make a new Quadrapus.
                      </div>
                    </div>
                    <div class='MazeContainer'>
                        <?php
                            echo renderMaze(createMaze());
                        ?>
                    </div>
                    <div class='ButtonContainer'>
                      <a class='Button' href="index.php">New Quadrapus!</a>
                    </div>
                </div>
            
            </div>
            
        </body>
    </head>
</html>