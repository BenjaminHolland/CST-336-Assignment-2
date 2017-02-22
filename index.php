<html>
    <head>
        <title>
            
        </title>
        <style type="text/css">
            @import 'root.css';
        </style>
        <?php
        
            function createMazeCellType($path,$top,$left,$bottom,$right){
                return array('path'=>$path,'edges'=>array('top'=>$top,'bottom'=>$bottom,'left'=>$left,'right'=>$right));
            }
            
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
                
            $size=array('w'=>21,'h'=>21);

            function writeMaze(&$maze,$pos,$move){
                //echo '<p>';
                global $size;
                global $mazeMoves;
                $newPos=array('x'=>($pos['x']+$move['x']),'y'=>($pos['y']+$move['y']));
                if($newPos['x']<0||$newPos['x']>=$size['w']) return;
                if($newPos['y']<0||$newPos['y']>=$size['h']) return;
                if($maze[$newPos['y']][$newPos['x']]!=null) return;
                $next=nextMazeCell($move);
              
                if($next['key']=='TR'&&($move==$mazeMoves['D'])){
                    //echo "TRD";
                    $move=$mazeMoves['R'];
                }else if($next['key']=='TR'&&($move==$mazeMoves['L'])){
                    //echo "TRL";
                    $move=$mazeMoves['U'];
                }else if($next['key']=='TL'&&($move==$mazeMoves['R'])){
                    //echo "TLR";
                    $move=$mazeMoves['U'];
                }else if($next['key']=='TL'&&($move==$mazeMoves['D'])){
                    //echo "TLD";
                    $move=$mazeMoves['L'];
                }else if($next['key']=='BR'&&($move==$mazeMoves['U'])){
                    //echo "BRU";
                    $move=$mazeMoves['R'];
                }else if($next['key']=='BR'&&($move==$mazeMoves['L'])){
                    //echo "BRL";
                    $move=$mazeMoves['D'];
                }else if($next['key']=='BL'&&($move==$mazeMoves['U'])){
                    //echo "BLU";
                    $move=$mazeMoves['L'];
                }else if($next['key']=='BL'&&($move==$mazeMoves['R'])){
                  //  echo "BLR";
                    $move=$mazeMoves['D'];
                }
                
               // var_dump($move);
               // echo '<p>';
               // var_dump($newPos);
              //  echo '</p>';
                $maze[$newPos['y']][$newPos['x']]=$next['value']['path'];
                writeMaze($maze,$newPos,$move);  
                
               //     echo '</p>';
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
               // var_dump($cellChoices);
               $key=array_rand($cellChoices);
                return array('key'=>$key,'value'=>$mazeCells[$key]);
            }
            
            
            function createMaze(){
                global $mazeMoves;
                global $size;
                $maze=array();
                for($row=0;$row<$size['h'];$row++){
                    array_push($maze,array());
                    for($col=0;$col<$size['w'];$col++){
                        array_push($maze[$row],null);
                    }
                }
                
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
            <div class='MazeContainer'>
            <?php
            echo renderMaze(createMaze());
            ?>
            </div>
            
            
        </body>
    </head>
</html>