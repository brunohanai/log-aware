<?php

namespace brunohanai\LogAware\Config;

use brunohanai\LogAware\Action\LogAction;
use brunohanai\LogAware\Action\MailAction;
use brunohanai\LogAware\Action\SlackAction;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class ConfigDefinition implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();

        $rootNode = $treeBuilder->root(Config::ROOT_KEY);

        $rootNode
            ->children()
                ->arrayNode(Config::SYSTEM_KEY)
                    ->children()
                        ->variableNode(Config::SYSTEM_LOG_FILEPATH_KEY)
                            ->defaultValue(Config::SYSTEM_LOG_FILEPATH_DEFAULT)
                        ->end()
                        ->enumNode(Config::SYSTEM_LOG_LEVEL_KEY)
                            ->values(array('emergency', 'alert', 'critical', 'error', 'warning', 'notice', 'info', 'debug'))
                            ->defaultValue(Config::SYSTEM_LOG_LEVEL_DEFAULT)
                        ->end()
                    ->end()
                ->end()
                ->arrayNode(Config::ACTIONS_KEY)
                    ->requiresAtLeastOneElement()
                    ->isRequired()
                    ->canNotBeEmpty()
                    ->prototype('array')
                        ->children()
                            ->enumNode(Config::ACTIONS_TYPE_KEY)
                                ->values(array(Config::ACTION_TYPE_SLACK, Config::ACTION_TYPE_MAIL, Config::ACTION_TYPE_LOG))
                                ->isRequired()
                                ->cannotBeEmpty()
                                ->beforeNormalization()
                                    ->always()
                                    ->then(function ($v) { return strtolower($v); })
                                ->end()
                            ->end()
                            ->arrayNode(Config::ACTIONS_OPTIONS_KEY)
                                ->isRequired()
                                ->cannotBeEmpty()
                                ->children()
                                    ->variableNode(SlackAction::OPTION_WEBHOOK_KEY)->end()
                                    ->variableNode(SlackAction::OPTION_CHANNEL_KEY)->end()
                                    ->variableNode(SlackAction::OPTION_ICON_KEY)->end()
                                    ->variableNode(SlackAction::OPTION_USERNAME_KEY)->end()
                                    ->variableNode(SlackAction::OPTION_CHANNEL_KEY)->end()
                                    ->enumNode(LogAction::OPTION_LEVEL_KEY)->values(array('emergency', 'alert', 'critical', 'error', 'warning', 'notice', 'info', 'debug'))->end()
                                    ->variableNode(LogAction::OPTION_FILEPATH_KEY)->end()
                                    ->variableNode(MailAction::OPTION_SUBJECT_KEY)->end()
                                    ->variableNode(MailAction::OPTION_TO_KEY)->end()
                                    ->variableNode(MailAction::OPTION_FROM_KEY)->end()
                                    ->variableNode(MailAction::OPTION_HOST_KEY)->end()
                                    ->variableNode(MailAction::OPTION_PORT_KEY)->end()
                                    ->variableNode(MailAction::OPTION_USERNAME_KEY)->end()
                                    ->variableNode(MailAction::OPTION_PASSWORD_KEY)->end()
                                ->end()
                            ->end()
                        ->end()
                        ->validate()
                            ->ifTrue(function($v) { return $v[Config::ACTIONS_TYPE_KEY] == Config::ACTION_TYPE_SLACK && !isset($v[Config::ACTIONS_OPTIONS_KEY][SlackAction::OPTION_WEBHOOK_KEY]); })
                            ->thenInvalid('"options.webhook_url" must be set when "type" is [slack].')
                        ->end()
                        ->validate()
                            ->ifTrue(function($v) { return $v[Config::ACTIONS_TYPE_KEY] == Config::ACTION_TYPE_SLACK && !isset($v[Config::ACTIONS_OPTIONS_KEY][SlackAction::OPTION_CHANNEL_KEY]); })
                            ->thenInvalid('"options.channel" must be set when "type" is [slack].')
                        ->end()
                        ->validate()
                            ->ifTrue(function($v) { return $v[Config::ACTIONS_TYPE_KEY] == Config::ACTION_TYPE_SLACK && !isset($v[Config::ACTIONS_OPTIONS_KEY][SlackAction::OPTION_USERNAME_KEY]); })
                            ->thenInvalid('"options.username" must be set when "type" is [slack].')
                        ->end()
                        ->validate()
                            ->ifTrue(function($v) { return $v[Config::ACTIONS_TYPE_KEY] == Config::ACTION_TYPE_SLACK && !isset($v[Config::ACTIONS_OPTIONS_KEY][SlackAction::OPTION_ICON_KEY]); })
                            ->thenInvalid('"options.icon_emoji" must be set when "type" is [slack].')
                        ->end()
                        ->validate()
                            ->ifTrue(function($v) { return $v[Config::ACTIONS_TYPE_KEY] == Config::ACTION_TYPE_LOG && !isset($v[Config::ACTIONS_OPTIONS_KEY][LogAction::OPTION_FILEPATH_KEY]); })
                            ->thenInvalid('"options.filepath" must be set when "type" is [log].')
                        ->end()
                        ->validate()
                            ->ifTrue(function($v) { return $v[Config::ACTIONS_TYPE_KEY] == Config::ACTION_TYPE_LOG && !isset($v[Config::ACTIONS_OPTIONS_KEY][LogAction::OPTION_LEVEL_KEY]); })
                            ->thenInvalid('"options.level" must be set when "type" is [log].')
                        ->end()
                        ->validate()
                            ->ifTrue(function($v) { return $v[Config::ACTIONS_TYPE_KEY] == Config::ACTION_TYPE_MAIL && !isset($v[Config::ACTIONS_OPTIONS_KEY][MailAction::OPTION_SUBJECT_KEY]); })
                            ->thenInvalid('"options.subject" must be set when "type" is [mail].')
                        ->end()
                        ->validate()
                            ->ifTrue(function($v) { return $v[Config::ACTIONS_TYPE_KEY] == Config::ACTION_TYPE_MAIL && (!isset($v[Config::ACTIONS_OPTIONS_KEY][MailAction::OPTION_TO_KEY]) || !isset($v[Config::ACTIONS_OPTIONS_KEY][MailAction::OPTION_FROM_KEY])); })
                            ->thenInvalid('"options.from" and "options.to" must be set when "type" is [mail].')
                        ->end()
                        ->validate()
                            ->ifTrue(function($v) { return $v[Config::ACTIONS_TYPE_KEY] == Config::ACTION_TYPE_MAIL && (!isset($v[Config::ACTIONS_OPTIONS_KEY][MailAction::OPTION_HOST_KEY]) || !isset($v[Config::ACTIONS_OPTIONS_KEY][MailAction::OPTION_PORT_KEY])); })
                            ->thenInvalid('"options.host" and "options.port" must be set when "type" is [mail].')
                        ->end()
                        ->validate()
                            ->ifTrue(function($v) { return $v[Config::ACTIONS_TYPE_KEY] == Config::ACTION_TYPE_MAIL && (!isset($v[Config::ACTIONS_OPTIONS_KEY][MailAction::OPTION_USERNAME_KEY]) || !isset($v[Config::ACTIONS_OPTIONS_KEY][MailAction::OPTION_PASSWORD_KEY])); })
                            ->thenInvalid('"options.username" and "options.password" must be set when "type" is [mail].')
                        ->end()
                    ->end()
                ->end()
                ->arrayNode(Config::FILES_KEY)
                    ->requiresAtLeastOneElement()
                    ->isRequired()
                    ->canNotBeEmpty()
                    ->prototype('array')
                        ->children()
                            ->variableNode(Config::FILES_FILEPATH_KEY)
                                ->isRequired()
                                ->canNotBeEmpty()
                            ->end()
                            ->arrayNode(Config::FILES_FILTERS_KEY)
                                ->requiresAtLeastOneElement()
                                ->isRequired()
                                ->canNotBeEmpty()
                                ->prototype('array')
                                    ->children()
                                        ->variableNode(Config::FILES_FILTERS_DESCRIPTION_KEY)
                                            ->isRequired()
                                            ->canNotBeEmpty()
                                        ->end()
                                        ->scalarNode('regex')
                                            ->isRequired()
                                            ->canNotBeEmpty()
                                            ->validate()
                                                ->ifTrue(function($v) {
                                                    preg_match_all('/\/.*\//', $v, $check);

                                                    return count($check[0]) === 0;
                                                })->thenInvalid('Invalid Regex.')
                                            ->end()
                                        ->end()
                                        ->arrayNode('actions')
                                            ->isRequired()
                                            ->prototype('scalar')->end()
                                            ->end()
                                        ->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }

    public function checkActionOption($vars)
    {

    }

    public function addActionOptionsNode($action_type = null)
    {
        $builder = new TreeBuilder();

        $node = $builder->root(Config::ACTIONS_OPTIONS_KEY);

        switch($action_type) {
            case Config::ACTION_TYPE_SLACK:
                $node
                    ->isRequired()
                    ->children()
                        ->variableNode(SlackAction::OPTION_WEBHOOK_KEY)->isRequired()->cannotBeEmpty()->end()
                        ->variableNode(SlackAction::OPTION_CHANNEL_KEY)->isRequired()->cannotBeEmpty()->end()
                        ->variableNode(SlackAction::OPTION_ICON_KEY)->defaultValue(SlackAction::OPTION_ICON_DEFAULT)->end()
                        ->variableNode(SlackAction::OPTION_USERNAME_KEY)->defaultValue(SlackAction::OPTION_USERNAME_DEFAULT)->end()
                    ->end()
                ;
                return $node;
            default:
                break;
        }
    }
}