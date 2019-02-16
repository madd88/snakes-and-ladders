<?php

/**
 *  Simple snakes and ladders game on PHP CLI
 *
 * Rules
 * You must continue to roll the dice every second till you reach position 100 exactly, you start at position 1
 * If your new position after the roll divides by 9 (9, 18, 27, 36…) you landed on a snake and must move back 3 places
 * If your new position after the roll is 25 or 55 you must move forward 10 places
 * If your roll is near the end of the game and you do not roll enough to move exactly to 100, you do not move forward
 *
 */

namespace Core\Game;

class Game
{

    /**
     * @var int $size - max position on board
     */

    private $size = 100;

    /**
     * Game constructor.
     *
     * Sets first position for all players
     *
     * @param $players
     */

    public function __construct($players)
    {
        $step = [];

        foreach ($players as $player) {
            $step[$player] = 1;
        }

        $this->start($players, $step);
    }

    /**
     * Get roll
     *
     * @return int - random int from 1 to 6
     */

    public function rollDice() : int
    {
        return rand(1, 6);
    }

    /**
     * Main application logic
     * Recursive
     *
     * @param       $players
     * @param array $step
     *
     * @return bool
     */

    public function start(array $players, array $step = [])
    {

        foreach ($players as $player) {

            $dice = $this->rollDice();
            $newPosition = $this->getPosition($dice, $step[$player]);
            $step[$player] = $newPosition['position'];
            $positionOut = implode("", $newPosition);

            $this->output($player, $dice, $positionOut);

            $winner = $this->checkWinner($step);

            if ($winner !== false) {
                print_r("" . $winner[0]);

                return true;
            }
        }

        return $this->start($players, $step);

    }

    /**
     *  Calculating a new position
     *
     * @param $dice
     * @param $position
     *
     * @return array
     */

    public function getPosition(int $dice, int $position) : array
    {

        $newPosition = $dice + $position;
        if ($newPosition > $this->size) {
            $result = ["sl" => "", "position" => $position];
        } elseif ($newPosition == 25 || $newPosition == 55) {
            $result = ["sl" => "ladder", "position" => ($newPosition + 10)];
        } elseif ($newPosition % 9 === 0) {
            $result = ["sl" => "snake", "position" => ($newPosition - 3)];
        } else {
            $result = ["sl" => "", "position" => $newPosition];
        }

        return $result;

    }

    /**
     * Checking for a winner!!!
     *
     * @param $step
     *
     * @return array|bool
     */

    public function checkWinner(array $step)
    {

        $result = false;

        if (count($step) > 0) {
            foreach ($step as $name => $position) {
                if ($position === 100) return [$name, $position];
            }
        }

        return $result;

    }

    /**
     * Console output
     *
     * @param $player
     * @param $dice
     * @param $position
     */

    public function output($player, $dice, $position)
    {
        echo($dice . $player .  "-" . $position . "\n");
    }

}